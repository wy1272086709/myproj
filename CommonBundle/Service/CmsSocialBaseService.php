<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */

namespace CommonBundle\Service;


use ApiBundle\api\ApiProblem;
use ApiBundle\Exception\ApiProblemException;
use ApiBundle\Form\SocialForm;
use CommonBundle\Entity\CmsSocialBase;
use CommonBundle\Entity\CmsSocialImgExt;
use CommonBundle\Entity\CmsSocialImgTagMap;
use CommonBundle\Entity\CmsSocialTag;
use CommonBundle\Repository\CmsSocialBaseRepository;
use CommonBundle\Repository\CmsSocialImgExtRepository;
use CommonBundle\Repository\CmsSocialTagRepository;
use CommonBundle\Utils\AppOs;
use CommonBundle\Utils\CacheKeyPreConfig;
use CommonBundle\Utils\ErrorCode;
use CommonBundle\Utils\ExceptionHelp;
use CommonBundle\Utils\PicDomain;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyInfo\Tests\Extractor\ReflectionExtractorTest;

class CmsSocialBaseService extends BaseDbService
{
    protected $filterService = null;
    CONST SOCIAL_BASE_KEY_PRE = "app64:cmsSocial:";
    CONST SOCIAL_BASE_EXPIRE = 3600;
    CONST SOCIAL_BASE_HARF_HOUR_EXPIRE = 1800;
    //待审核
    CONST UNCHECK_STATUS = 0;
    //审核通过
    CONST PASS_STATUS = 1;
    //审核不通过
    CONST NOT_PASS_STATUS = 2;

    const ACTIVITY_TAG = 1;
    const CONTENT_TAG = 2;
    const USER_TAG = 3;


    const SHARE_URL_DOMAIN = 'https://mapp.to8to.com/';
    const SHARE_UGC_MEITU_DETAIL = self::SHARE_URL_DOMAIN.'ugcMeitu/detail/';
    const TAG_AGG_URL = self::SHARE_URL_DOMAIN.'ugcMeitu/tagAgg/';

    public function __construct(ContainerInterface $container, FilterService $filterService)
    {
        parent::__construct($container);
        $this->filterService = $filterService;
    }


    /**
     * add baseinfo
     * @param $params
     * @return object
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addBaseInfo($params)
    {
        $this->filterSensitive($params);
        if (isset($params['cityId']) && $params['cityId']) {
            $params['cityId'] = $this->getStandIdFromT8tCityId($params['cityId']);
        }
        $params['publishTime'] = time();
        //检测标签值是否合法
        if (isset($params['contentTags']) && $params['contentTags']) {

            if ($tags = $this->checkContentTag($params)) {
                $params['contentTags'] = $tags;
            }
        };
        /**
         * @var  $repository CmsSocialBaseRepository
         */
        $repository = $this->getEntityRepository(CmsSocialBase::class);

        return $repository->addBaseInfo($params);
    }

    /**
     * check content_tag field
     * @param $params
     * @return bool|string
     */
    public function checkContentTag($params)
    {

        /**
         * @var  $repository CmsSocialTagRepository
         */
        $repository = $this->getEntityRepository(CmsSocialTag::class);
        $contentTagsArr = explode(',', $params['contentTags']);
        $result = $repository->batchExist($contentTagsArr, $params['tagType']);
        if (empty($result)) {
            return false;
        }
        $tmp = [];
        if ($result) {
            $resultContent = array_column($result, 'tagContent');
            foreach ($contentTagsArr as $item) {
                if (in_array($item, $resultContent)) {
                    $tmp[] = $item;
                }
            }
            $tagContentStr = implode(',', $tmp);

            return $tagContentStr;
        }

        return false;
    }


    public function getStandIdFromT8tCityId($cityId)
    {
        $cityService = $this->container->get(CityService::class);
        $result = $cityService->getStandCityIdFromT8TCityId($cityId);
        if (isset($result[0])) {
            $standardIdArr = $result[0];
            $cityId = $standardIdArr['cid'] ?? 0;
        }

        return $cityId;
    }

    /**
     * filter sensitive word
     * @param $params
     * @return bool
     */
    public function filterSensitive($params)
    {
        $tagContent = ['content' => $params['content']];
        $result = $this->filterService->filterDocument($tagContent);
        if ($result['content']['needWarning']) {
            ExceptionHelp::throwApiProblemException(ErrorCode::CMS_SOCIAL_HIT_SENSITIVE);
        }

        return true;
    }

    /**
     * get detail info from db
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getDetailInfoFromDb($params)
    {
        /**
         * @var  $repository CmsSocialBaseRepository
         */
        $repository = $this->getEntityRepository(CmsSocialBase::class);
        $detailInfo = $repository->getDetailInfo($params);
        if ($detailInfo) {
            $detailInfo['baseInfo'] = $this->fetchBaseInfo($detailInfo['baseInfo']);
            $detailInfo['imgTagInfo'] = $this->fetchImgTagInfo($detailInfo['imgTagInfo']);
            $detailInfo = SocialForm::translateDetailResponse($detailInfo);

            return $detailInfo;
        }

        return [];
    }

    /**
     * fetch base info
     * @param $baseInfo CmsSocialBase
     * @return array
     */
    public function fetchBaseInfo($baseInfo)
    {
        $mergeInfo = [];
        $mergeInfo['baseId'] = $baseInfo->getId();
        $mergeInfo['authorId'] = $baseInfo->getAuthorId();
        $mergeInfo['authorName'] = $baseInfo->getAuthorName();
        $mergeInfo['authorAvatar'] = $baseInfo->getAuthorAvatar();
        $mergeInfo['content'] = $baseInfo->getContent();
        $mergeInfo['content'] = self::filterHtml($mergeInfo['content']);
        $mergeInfo['contentTags'] = $this->getTagOtherInfoArr($baseInfo->getContentTags());
        $mergeInfo['status'] = $baseInfo->getStatus();

        return $mergeInfo;
    }

    /**
     * filter html
     * 过滤HTML
     * @param string $str
     * @return string
     */
    public static function filterHtml($str)
    {
        $str = strip_tags($str);
        $str = str_replace('&nbsp;', ' ', $str);
        return $str;
    }

    /**
     * @param $content
     * @return array|bool
     */
    public function getContentTagsInfo($content)
    {
        if (empty($content)) {
            return [];
        }
        /**
         * @var  CmsSocialTagRepository $responsity
         */
        $responsity = $this->getEntityRepository(CmsSocialTag::class);
        $contentArr = explode(',', $content);

        $result = $responsity->batchGetTags($contentArr, CmsSocialBaseService::CONTENT_TAG);

        if (empty($result)) {
            return [];
        }
        $tmp = [];
        foreach ($result as $item) {
            $key = array_search($item['tagContent'], $contentArr);
            if ($key !== false) {
                $tmp[$key] = $item;
            }
        }

        if (empty($tmp)) {
            return [];
        }

        return self::keySort($tmp);

    }

    public static function keySort($tmp)
    {
        $result = [];
        foreach ($tmp as $item) {
            $result[] = $item;
        }

        return $result;
    }

    public function getTagOtherInfoArr($content)
    {
        if (empty($content)) {
            return [];
        }

        $tagInfo = $this->getContentTagsInfo($content);

        if (empty($tagInfo)) {
            return [];
        }

        $tagInfo = $this->changeTagidKey($tagInfo);

        return $this->getTagData($tagInfo);
    }

    public function changeTagidKey($tagInfo)
    {
        foreach ($tagInfo as &$item) {
            $item['tagId'] = $item['id'];
            unset($item['id']);
        }

        return $tagInfo;
    }


    /**
     * get detail info by params
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getDetailInfo($params)
    {
        $key = $this->getRedisKeyOfDetailInfo($params);

        $selectFromCached = $params['selectFromCached'] ?? 1;
        $detailInfo = [];

        if ($selectFromCached && !$detailInfo = $this->hasCache($key)) {
            $detailInfo = $this->getDetailInfoFromDb($params);
        } else {
            $detailInfo = $this->getDetailInfoFromDb($params);
        }

        if ($detailInfo) {
            $this->cache($key, $detailInfo, self::SOCIAL_BASE_EXPIRE);
        } else {
            return [];
        }

        $detailInfo = $this->getUserInfo($detailInfo);

        $interaction = $this->getInteraction($params);
        $detailInfo['collectStatus'] = $interaction['collectStatus'];
        $detailInfo['collectNum'] = $interaction['collectNum'];
        $detailInfo['commentNum'] = $interaction['commentNum'];
        $detailInfo['praiseNum'] = $interaction['praiseNum'];

        $shareInfo = $this->getShareInfo($detailInfo);
        $detailInfo['shareUrl'] = $shareInfo['shareUrl'];
        $detailInfo['shareTitle'] = $shareInfo['shareTitle'];
        $detailInfo['shareContent'] = $shareInfo['shareContent'];
        $detailInfo['sharePic'] = $shareInfo['sharePic'];

        return $detailInfo;
    }

    public function getUserInfo($data)
    {
        if (!isset($data['authorId'])) {
            return $data;
        }

        try {
            return $this->container->get(SocialService::class)->mergeUserInfo($data);
        } catch (ApiProblemException $e) {
            /**
             * @var ApiProblem $apiProblem
             */
            $apiProblem = $e->getApiProblem();
            $msg = "faild to get user info,code:{$apiProblem->getErrorCode()},msg:{$apiProblem->getExtraMsg()},{$apiProblem->getErrorMsg()},file:{$e->getFile(
                    )},line:{$e->getLine()}";
            $this->getLogger()->error($msg);
        }

        return $data;

    }

    public function getShareInfo($detailInfo)
    {
        $shareInfo = [];
        $shareInfo['shareUrl'] = '';
        $shareInfo['shareTitle'] = '';
        $shareInfo['shareContent'] = '';
        if ($detailInfo['authorName']) {
            $shareInfo['shareContent'] = "来自\"{$detailInfo['authorName']}\"的图文";
        }
        $shareInfo['sharePic'] = '';
        if (isset($detailInfo['imgInfo'])) {
            if (isset($detailInfo['imgInfo'][0])) {
                $shareInfo['sharePic'] = $detailInfo['imgInfo'][0]['imgPath'] ?? '';
                $shareInfo['sharePic'] = PicDomain::strcat300w300hRule($shareInfo['sharePic']);
            }
        }

        return InteractionService::getUgcShareInfo(
            $detailInfo['baseId'],
            $shareInfo['sharePic'],
            $shareInfo['shareTitle'],
            $shareInfo['shareContent']
        );
    }

    public function getUgcMeituShareUrl($baseId)
    {
        if ($baseId) {
            return self::SHARE_UGC_MEITU_DETAIL.$baseId;
        }

        return '';
    }

    public function getInteraction($params)
    {
        /**
         * @var InteractionService $interactionService
         */
        $baseId = $params['baseId'] ?? '';
        $uid = $params['uid'] ?? '';
        $interactionService = $this->container->get(InteractionService::class);
        $result = $interactionService->setIsReturnAllFields(false)->getObjectCount(
            InteractionService::CODE_UGC_MEITU,
            $baseId,
            $uid
        );

        /*$result['collectNum'] = $this->changeUnit($result['collectNum']);
        $result['commentNum'] = $this->changeUnit($result['commentNum']);
        $result['praiseNum'] = $this->changeUnit($result['praiseNum']);*/

        return $result;
    }

    public function changeUnit($num, $max = 10000)
    {
        if (empty($num)) {
            return $num;
        }

        if ($num > $max) {
            $num = sprintf('%.1f', $num / $max);
            $num = $num.'W';
        } elseif ($num == $max) {
            $num = sprintf('%d', $num / $max);
            $num = $num.'W';
        }

        return $num;
    }

    public function fetchImgTagInfo($imgTagInfo)
    {
        if (empty($imgTagInfo)) {
            return [];
        }
        $mergeInfo = [];
        /**
         * @var  $item CmsSocialImgTagMap
         */
        foreach ($imgTagInfo as $item) {
            $tmp = [];
            $imgId = $item->getImgId();
            $tmp['xCoordinate'] = $item->getXCoordinate();
            $tmp['yCoordinate'] = $item->getYCoordinate();
            $tmp['tagId'] = $item->getTagId();
            $tmp['tagContent'] = $item->getTagContent();
            $tmp['tagDirection'] = $item->getTagDirection();
            $tmp['tagType'] = $item->getTagType();
            $mergeInfo[$imgId][] = $tmp;
        }

        foreach ($mergeInfo as $k => $item) {
            $mergeInfo[$k] = $this->getTagData($item);
        }

        return $mergeInfo;
    }

    public function getTagData($tagArr)
    {
        if (empty($tagArr)) {
            return $tagArr;
        }

        $activityUrlList = $this->getTagActivitUrlList($tagArr);
        $tagAggUrlList = $this->getTagAggUrlList($tagArr);


        $newTagArr = [];
        foreach ($tagArr as $key => $item) {
            if (isset($item['tagId'])) {
                $tagId = $item['tagId'];
                $item['url'] = $activityUrlList[$tagId] ?? ($tagAggUrlList[$tagId] ?? '');
                $newTagArr[] = $item;
            }
        }

        return $newTagArr;
    }

    /**
     * @param $tagArr
     * @return array
     */
    public function getTagAggUrlList($tagArr)
    {
        $tagAggIdArr = $this->getTagAggIdArr($tagArr);
        if (empty($tagAggIdArr)) {
            return [];
        }

        return $this->getTagAggUrl($tagAggIdArr);
    }

    public function getTagAggUrl($tagAggIdArr)
    {
        if (empty($tagAggIdArr)) {
            return [];
        }
        $urlList = [];
        foreach ($tagAggIdArr as $tagId) {
            $urlList[$tagId] = self::TAG_AGG_URL.$tagId;
        }

        return $urlList;
    }

    /**
     * @param array $tagArr
     * @return array
     */
    public function getTagAggIdArr($tagArr)
    {
        $tagIdArr = [];
        foreach ($tagArr as $tag) {
            if (!$this->isActivityTag($tag['tagType'])) {
                if (isset($tag['tagId'])) {
                    $tagId = $tag['tagId'];
                    $tagIdArr[] = $tagId;
                }
            }
        }

        return $tagIdArr;
    }

    /**
     * @param $tagArr
     * @return array
     */
    public function getTagActivitUrlList($tagArr)
    {
        if (empty($tagArr)) {
            return $tagArr;
        }

        $activityTagId = $this->getActivityTagId($tagArr);

        if (empty($activityTagId)) {
            return [];
        }

        return $this->getActivityUrl($activityTagId);

    }

    public function getActivityTagId($tagArr)
    {
        $activityTagId = [];
        foreach ($tagArr as $tag) {
            if ($this->isActivityTag($tag['tagType'])) {
                if (isset($tag['tagId'])) {
                    $tagId = $tag['tagId'];
                    $activityTagId[] = $tagId;
                }
            }
        }

        return $activityTagId;

    }

    public function getActivityUrl($tagIdArr)
    {
        try {
            $activityService = $this->container->get(ActivityService::class);
            $urlList = $activityService->buildUrlByLabelIds($tagIdArr)->getData();
            $this->container->get('logger')->info(
                "获取活动征集链接url成功,tagIdArr:,".var_export($tagIdArr, true).",res:".var_export($urlList, true)
            );

            return $urlList;
        } catch (\Exception $e) {
            $this->container->get('logger')->error(
                "获取活动征集链接url失败,exception:".$e->getMessage().',trance:'.$e->getTraceAsString()
            );
        }


        return [];
    }

    public function isActivityTag($tagType)
    {
        if ($tagType == self::ACTIVITY_TAG) {
            return true;
        }

        return false;
    }

    public function getDetaultSize($params)
    {
        $size = $params['size'] ?? 9;

        return $size;
    }

    /**
     * @param $params
     * @return array|mixed|string
     * @throws \Exception
     */
    public function getRecomendImgByLatest($params)
    {
        $latestBaseInfo = $this->getLatestBaseInfo($params);
        if (empty($latestBaseInfo)) {
            return [];
        }

        $latestImgInfo = $this->getLatestImg($latestBaseInfo, $params);

        return $latestImgInfo;
    }

    /**
     * @param array $latestBaseInfo
     * @param $params
     * @return array|mixed|string
     * @throws \Exception
     */
    public function getLatestImg($latestBaseInfo, $params)
    {

        /**
         * @var  $repository CmsSocialImgExtRepository
         */
        $repository = $this->getEntityRepository(CmsSocialImgExt::class);
        $size = $this->getDetaultSize($params);
        $baseIds = $this->getRantBaseId($latestBaseInfo, $size);
        if (!$baseIds) {
            return [];
        }
        $latestImgInfo = $repository->createQueryBuilder('p')->select("p")->where(
            " p.baseId in (:baseIds)"
        )->setParameter("baseIds", $baseIds)->getQuery()->getResult();
        $appostype = $params['appostype'] ?? 2;
        $latestImgInfo = SocialForm::translateLatestResponse($latestImgInfo, $appostype);

        return $latestImgInfo;

    }


    public function getRantBaseId($latestBaseInfo, $size)
    {

        if (empty($latestBaseInfo)) {
            return [];
        }
        $ids = [];
        if (count($latestBaseInfo) > $size) {
            $keysArr = array_rand($latestBaseInfo, $size);
            foreach ($keysArr as $key) {
                if (isset($latestBaseInfo[$key])) {
                    $item = $latestBaseInfo[$key];
                    $ids[] = $item['id'];
                }
            }
        } else {
            foreach ($latestBaseInfo as $item) {
                /**
                 * @var CmsSocialBase $item
                 */
                $ids[] = $item['id'];
            }
        }

        return $ids;
    }

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getLatestBaseInfo($params)
    {

        $entityClass = CmsSocialBase::class;
        /**
         * @var  $repository CmsSocialBaseRepository
         */
        $repository = $this->getEntityRepository($entityClass);
        $key = $this->getRedisKeyOfLatestBaseInfo();
        if (!$latestBaseInfo = $this->hasCache($key)) {
            $page = $params['page'] ?? 1;
            $size = $this->getDetaultSize($params);
            $offset = ($page - 1) * $size;

            $limit = 100;
            $latestBaseInfo = $repository->createQueryBuilder('p')->select("p.id")->where(
                "p.status=:status and p.baseType = 1 and p.id != :id"
            )->setParameters(
                ['status' => self::PASS_STATUS, 'id' => $params['baseId']]
            )->orderBy('p.createTime', 'desc')->setFirstResult($offset)->setMaxResults($limit)->getQuery(
            )->getArrayResult();

            if (count($latestBaseInfo) > $size) {
                $this->cache($key, $latestBaseInfo, self::SOCIAL_BASE_HARF_HOUR_EXPIRE);
            }
        }

        return $latestBaseInfo;
    }


    /**
     * @param $params
     * @return string
     */
    public function getRedisKeyOfDetailInfo($params)
    {
        $pre = $this->getRedisPreOfDetailInfo();
        $baseId = $params['baseId'] ?? '';
        $key = "baseId_{$baseId}";

        return "{$pre}:{$key}";
    }

    /**
     * @return string
     */
    public function getRedisKeyOfLatestBaseInfo()
    {
        $pre = $this->getRedisPreOfDetailInfo();
        $key = "getLatestBaseInfo";

        return "{$pre}:{$key}";
    }


    /**
     * @return string
     */
    public function getRedisPreOfDetailInfo()
    {
        $pre = CacheKeyPreConfig::APP64_CMS_SOCIAL.'getDetailInfo';

        return $pre;
    }

    /**
     * @return CmsSocialBaseRepository | object
     */
    public function getSelfEntity()
    {
        return $this->getEntityRepository(CmsSocialBase::class);
    }


    public function getBaseInfoByBaseIdArr($baseIdArr, $statusArr)
    {
        //获取用户发布的美图
        $meituData = $this->getSelfEntity()->getMeituByBaseIdArr($baseIdArr, $statusArr);
        //根据baseId重新排序
        if (empty($meituData)) {
            return [];
        }

        return $this->reindexArr($baseIdArr, $meituData);

    }

    public function reindexArr($sortKey, $data)
    {
        if (empty($sortKey)) {
            return [];
        }
        $newResult = [];

        foreach ($sortKey as $id) {
            foreach ($data as $item) {
                if ($item['id'] == $id) {
                    $newResult[] = $item;
                    break;
                }
            }
        }

        return $newResult;
    }


    public function reBuildCoverInfo($meituArr, $appOsType = AppOs::ANDROID_OS_TYPE)
    {
        if (empty($meituArr)) {
            return [];
        }
        foreach ($meituArr as $k => $item) {
            $coverInfo = PicDomain::getFixedWidthHeight(
                $item['coverWidth'],
                $item['coverHeight'],
                PicDomain::FIXED_WIDTH,
                500
            );
            $item['originWidth'] = $item['coverWidth'];
            $item['originHeight'] = $item['coverHeight'];
            $item['originCover'] = $item['cover'];
            $item['coverWidth'] = $coverInfo['width'];
            $item['coverHeight'] = $coverInfo['height'];
            $item['cover'] = AppOs::isAndroid($appOsType) ? PicDomain::get500wJpgThumbUpUrl(
                $item['cover']
            ) : PicDomain::get500wWebPThumbUpUrl($item['cover']);
            $meituArr[$k] = $item;
        }

        return $meituArr;
    }

}
