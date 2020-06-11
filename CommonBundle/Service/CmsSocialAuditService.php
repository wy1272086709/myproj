<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */
namespace CommonBundle\Service;

use CommonBundle\Entity\CmsSocialBase;
use CommonBundle\Entity\CmsSocialImg;
use CommonBundle\Repository\CmsSocialBaseRepository;
use CommonBundle\Utils\TimeHelp;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CommonBundle\Utils\PicDomain;
use CommonBundle\Entity\CmsSocialVideo;
class CmsSocialAuditService extends BaseDbService
{
    /**
     * 依然是令人难受的魔法数字配置
     * 人工审核状态与内容状态的映射
     */
    protected static $manualStatusToStatus = [
        //人工审核不通过 => 不通过
        0 => 2,
        //人工审核通过 => 通过
        1 => 1,
    ];
    const VIDEO_TYPE   = 3;
    const ARTICLE_TYPE = 2;
    protected static $socialColumnsMap = [
        'baseType' => [0 => '其他', 1 => '图文', 2 => '文章', 3 => '视频'],
        'manualStatus' => [0 => '人工审核不通过', 1 => '人工审核已通过'],
        'status' => [0 => '审核中', 1 => '发布成功', 2 => '不通过', 3 => '删除', 4 => '草稿'],
    ];
    protected  $socialService;
    public function __construct(ContainerInterface $container, SocialService $service)
    {
        parent::__construct($container);
        $this->socialService = $service;
    }

    protected static $repositoryInstance = null;
    /**
     * @return \CommonBundle\Repository\CmsSocialBaseRepository|null|\Doctrine\ORM\EntityRepository
     */
    public  function getRepository()
    {
        if (self::$repositoryInstance == null) {
            self::$repositoryInstance = $this->getEntityManager()->getRepository(CmsSocialBase::class);
        }

        return self::$repositoryInstance;
    }

    public function getSocialAuditList(array $queryParams, int $status = 1)
    {
        $repository = $this->getRepository();
        $res        = $repository->getSocialAuditList($queryParams, $status);
        foreach ($res as $k => $row)
        {
            $res[$k]['cover'] = PicDomain::PIC_DOMAIN_HTTPS. $row['cover'];
            $row['publishTime']> 0 ? $res[$k]['publishTime'] = date('Y-m-d H:i:s', $row['publishTime']): $res[$k]['publishTime'] = '';
            $row['updateTime']> 0 ? $res[$k]['updateTime']  = date('Y-m-d H:i:s', $row['updateTime']): $res[$k]['updateTime'] = '';
            $res[$k]['baseTypeName'] = self::$socialColumnsMap['baseType'][$row['baseType']];
            $yidunRejectReason = json_decode($row['yidunRejectReason'], true);
            switch ($row['status']) {
                case 0:
                    //待审核
                    $yidunRejectReason['msg'] && $res[$k]['yidunStatusName'] = $this->dealYidunRejectMsg(
                        $yidunRejectReason['msg']
                    );
                    $res[$k]['manualStatusName'] = '';
                    break;

                case 1:
                    //审核通过
                    $res[$k]['yidunStatusName'] = $row['yidunStatus'] == 1 ?
                        $this->socialService->dealYidunRejectMsg('通过') :
                        ($yidunRejectReason['msg'] ? $this->dealYidunRejectMsg($yidunRejectReason['msg']) : '');
                    $res[$k]['manualStatusName'] = $row['manualStatus'] == 1 ? self::$socialColumnsMap['manualStatus'][$row['manualStatus']] : '';
                    break;

                case 2:
                    //审核不通过
                    $yidunRejectReason['msg'] && $res[$k]['yidunStatusName'] = $this->dealYidunRejectMsg(
                        $yidunRejectReason['msg']
                    );
                    if (mb_strlen($row['rejectReason'], 'UTF-8') > 10) {
                        $row['rejectReason'] = mb_substr($row['rejectReason'], 0, 10, 'UTF-8'). '...';
                    }
                    $res[$k]['manualStatusName'] = $row['manualStatus'] == 0 && $row['rejectReason'] ?
                        self::$socialColumnsMap['manualStatus'][$row['manualStatus']].'。原因：'.$row['rejectReason'] :
                        self::$socialColumnsMap['manualStatus'][$row['manualStatus']];
                    break;
            }
        }
        return $res;
    }

    /**
     * set  field status by condition
     * [可批量]基于主键id数组设置内容审核状态
     * @param array $reqParams 更新数据域
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function dealSocialStatus($reqParams)
    {
        if (empty($reqParams)) {
            return false;
        }
        isset(self::$manualStatusToStatus[$reqParams['manualStatus']]) &&
        $reqParams['status']     = self::$manualStatusToStatus[$reqParams['manualStatus']];
        $reqParams['updateTime'] = time();
        $baseIds  = $reqParams['baseIdArr'];
        $feedList = $this->socialService->getFeedDetails($baseIds);
        $status   = $reqParams['status'];
        $res = $this->getRepository()->dealSocialStatus($reqParams);
        $this->socialService->updateFeedFlowStatus($feedList, $status);
        return $res;
    }

    /**
     * set  recommendType value by condition
     * [可批量]基于主键id数组设置内容推荐状态
     * @param array $reqParams 更新数据域 recommendType, recommend_time, recommender_id, recommender_name
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function dealSocialRecommend($reqParams)
    {
        return $this->socialService->dealSocialRecommend($reqParams);
    }

    /**
     * get yidun reject msg
     * @param $yidunRejectMsg
     * @return string
     */
    public function dealYidunRejectMsg($yidunRejectMsg)
    {
        if (mb_strlen($yidunRejectMsg, 'UTF-8') > 10)
        {
            $yidunRejectMsg = mb_substr($yidunRejectMsg, 0, 10, 'UTF-8'). '...';
        }
        return '易盾：'. $yidunRejectMsg;
    }

    /**
     * get social audit count
     * @param array $queryParams
     * @param int $status
     * @return mixed
     */
    public function getSocialAuditCount(array $queryParams, int $status = 1)
    {
        $repository = $this->getRepository();
        $res        = $repository->getSocialAuditCount($queryParams, $status);
        return $res;
    }

    /**
     * get ugc detail
     * ugc内容详情
     * @param int $id
     * @return array
     */
    public function ugcDetail(int $id)
    {
        $params = compact('id');
        $baseEntity = $this->getRepository()->findOneBy($params);
        if (empty($baseEntity) || !is_object($baseEntity)) {
            return [];
        }
        $ugcDetail['id'] = $baseEntity->getId();
        $ugcDetail['content'] = $baseEntity->getContent();
        $ugcDetail['title'] = $baseEntity->getTitle();
        $ugcDetail['baseType'] = $baseEntity->getBaseType();
        $ugcDetail['status'] = $baseEntity->getStatus();
        $ugcDetail['authorId'] = $baseEntity->getAuthorId();
        $ugcDetail['authorName'] = $baseEntity->getAuthorName();
        $ugcDetail['authorAvatar'] = $baseEntity->getAuthorAvatar();
        $ugcDetail['original'] = $baseEntity->getOriginal();
        $ugcDetail['status'] = $baseEntity->getStatus();
        if ($ugcDetail['status'] == 3) {
            return [];
        }
        $cmsSocialImgExt = $baseEntity->getCmsSocialImgExt();
        $cmsSocialImg = $baseEntity->getCmsSocialImgs();
        $cmsSocialVideo = $baseEntity->getCmsSocialVideo();
        $ugcDetail['coverInfo'] = $this->socialService->formatCoverInfo($cmsSocialImgExt);
        if (!empty($ugcDetail['coverInfo']['cover'])) {
            $ugcDetail['coverInfo']['cover'] = PicDomain::PIC_DOMAIN. $ugcDetail['coverInfo']['cover'];
        }
        $imgInfo = $this->formatImgInfo($cmsSocialImg);
        if (is_array($imgInfo) && $imgInfo) {
            foreach ($imgInfo as $k => $img) {
                $imgInfo[$k]['imgPath'] = PicDomain::PIC_DOMAIN . $img['imgPath'];
            }
        }
        if (empty($cmsSocialVideo) || !is_object($cmsSocialVideo)) {
            $videoInfo = [];
        } else {
            $videoInfo = $this->formatVideoInfo($cmsSocialVideo);
            $env = \To8to\Yidun\Util::checkEnv();
            if ($env != 'production') {
                $videoDomain = 'https://hz-t8t-video-test.oss-cn-hangzhou.aliyuncs.com/';
            } else {
                $videoDomain = 'https://video.to8to.com/';
            }
            $videoInfo['videoUrl'] = $videoDomain. $videoInfo['videoUrl'];
            $videoInfo['videoCoverUrl'] = PicDomain::PIC_DOMAIN. $videoInfo['videoCoverUrl'];
        }
        if (empty($ugcDetail['coverInfo'])) {
            $ugcDetail['coverInfo'] = (object)[];
        }
        $ugcDetail['imgInfo'] = $imgInfo? $imgInfo: (object) [];
        $videoInfo && $imgInfo[] = $videoInfo;
        $sortArr = [];
        if ($imgInfo) {
            foreach ($imgInfo as $info) {
                $sortArr[] = $info['order'];
            }
        }
        array_multisort($sortArr, $imgInfo);
        $ugcDetail['videoInfo'] = $videoInfo? $videoInfo: (object) [];
        $ugcDetail['imgAndVideoInfo'] = $imgInfo;
        return $ugcDetail;
    }

    /**
     * format video info
     * @param object $cmsSocialVideo
     * @return array
     */
    public function formatVideoInfo(CmsSocialVideo $cmsSocialVideo)
    {
        /**
         * @var  $cmsSocialVideo CmsSocialVideo
         */
        $videoInfo['id'] = $cmsSocialVideo->getId();
        $videoInfo['videoUrl'] = $cmsSocialVideo->getVideoUrl();
        $videoInfo['videoCoverUrl'] = $cmsSocialVideo->getVideoCoverUrl();
        $videoInfo['videoCoverWidth'] = $cmsSocialVideo->getVideoCoverWidth();
        $videoInfo['videoCoverHeight'] = $cmsSocialVideo->getVideoCoverHeight();
        $videoInfo['description'] = $cmsSocialVideo->getDescription();
        $videoInfo['order']       = $cmsSocialVideo->getVideoOrder();
        $videoInfo['jobStatus']   = $cmsSocialVideo->getJobStatus();
        $videoInfo['type']        = self::VIDEO_TYPE;
        return $videoInfo;
    }

    /**
     * format img info
     * @param array $cmsSocialImg
     * @return array
     */
    public function formatImgInfo($cmsSocialImg)
    {
        if (empty($cmsSocialImg)) {
            return [];
        }
        $imgInfo = [];
        /**
         * @var  $item CmsSocialImg
         */
        foreach($cmsSocialImg as $item) {
            $tmp = [];
            if ($item instanceof CmsSocialImg) {
                $tmp['id'] = $item->getId();
                $tmp['imgPath'] = $item->getImgPath();
                $tmp['width']   = $item->getWidth();
                $tmp['height'] = $item->getHeight();
                $tmp['order']  = $item->getImgOrder();
                $tmp['description'] = $item->getDescription();
                $tmp['type']        = self::ARTICLE_TYPE;
            }
            $imgInfo[] = $tmp;
        }
        return $imgInfo;
    }

}