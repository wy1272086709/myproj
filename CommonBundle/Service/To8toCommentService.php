<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */
namespace CommonBundle\Service;
use CommonBundle\Entity\CmsYidun;
use CommonBundle\Entity\To8toAnswer;
use CommonBundle\Entity\To8toComment;
use CommonBundle\Repository\To8toCommentRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class To8toCommentService extends BaseService
{
    private $userService;
    public static $commentRepositoryInstance;
    public static $yidunRepositoryInstance;
    public static $to8toAnswerRepositoryInstance;
    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager,
                                UserService $userService)
    {
        parent::__construct($container);
        $this->userService            = $userService;
        $this->entityManager          = $entityManager;
    }

    /**
     * @return \CommonBundle\Repository\To8toCommentRepository|null|\Doctrine\ORM\EntityRepository
     */
    public function getTo8toCommentRepository()
    {
        if (self::$commentRepositoryInstance == null) {
            self::$commentRepositoryInstance = $this->container->get('doctrine')->getRepository(To8toComment::class, 'to8to');
        }
        return self::$commentRepositoryInstance;
    }

    /**
     * @return \CommonBundle\Repository\CmsYidunRepository|null|\Doctrine\ORM\EntityRepository
     */
    public function getYidunRepository()
    {
        if (self::$yidunRepositoryInstance == null) {
            self::$yidunRepositoryInstance = $this->getEntityManager()->getRepository(CmsYidun::class);
        }
        return self::$yidunRepositoryInstance;
    }

    /**
     * @return \CommonBundle\Repository\To8toAnswerRepository|null|\Doctrine\ORM\EntityRepository
     */
    public function getAnswerRepository()
    {
        if (self::$to8toAnswerRepositoryInstance == null) {
            self::$to8toAnswerRepositoryInstance = $this->container->get('doctrine')->getRepository(To8toAnswer::class, 'to8to');
        }
        return self::$to8toAnswerRepositoryInstance;
    }

    /**
     * get yidun check status result array
     * 获取易盾检测状态
     * @param $comType
     * @param $comIds
     */
    public function getYidunStatusNameList($comType, array $comIds)
    {
        $fields = [
            'checkDes',
            'checkTime',
            'objectId'
        ];
        $to8toRepository = $this->getTo8toCommentRepository();
        $yidunRepository = $this->getYidunRepository();
        $moduleCode = $to8toRepository->getModuleCodeByComtype($comType);
        $yidunStatusList = $yidunRepository->getLatestBatchYidunList($moduleCode, $comIds, $fields);
        return $yidunStatusList;
    }

    /**
     * get nickname list by uids
     * 获取昵称信息
     * @param array $uids
     * @return array
     */
    public function getNickNameList(array $uids)
    {
        $userInfoList = $this->userService->batchGetUserInfo($uids);
        $nickNameList = [];
        $userNameList = [];
        foreach ($userInfoList as $uid => $userInfo)
        {
            $nickNameList[$uid] = $userInfo['authorName'];
            $userNameList[$uid] = $userInfo['username'];
        }
        foreach ($uids as $uid)
        {
            if (!isset($nickNameList[$uid])||$nickNameList[$uid]==='')
            {
                $nickNameList[$uid] = isset($userNameList[$uid])?$userNameList[$uid]: '';
            }
        }
        return $nickNameList;
    }

    /**
     * Get the comment ID and the corresponding link page
     * 获取评论ID,对应的链接页面
     * @param $comType integer
     * @param $comId   integer 评论ID
     */
    public function getCommentIdUrl($comType, $askId, $url)
    {
        //PC问吧
        if ($comType == To8toCommentRepository::PC_ANSWER)
        {
            if ($askId)
            {
                $url = 'https://www.to8to.com/ask/k' . $askId . '.html';
            }
            else
            {
                $url = '';
            }
            return $url;
        }
        else if (in_array($comType, [ To8toCommentRepository::PC_ZXGL_ZXJJ, To8toCommentRepository::PC_ZXGL_XCSC ]))
        {
            if (strncmp($url, 'http', 4) !== 0)
            {
                $url = 'https:'.$url;
            }
            return $url;
        }
    }

    /**
     * Get a list of comments by condition, perge, page
     * @param array $condition
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function getCommentPageList(array $condition, $perPage = 10, $page = 1)
    {
        $res = $this->getTo8toCommentRepository()->queryPageListComment($condition, $perPage, $page);
        return $res;
    }

    /**
     * Get a list of comments by condition, perge, page
     * @param array $condition
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function getCommentPageResult(array $condition, $perPage = 10, $page = 1)
    {
        $list = $this->getCommentPageList($condition, $perPage, $page);
        if (!$list)
        {
            return [];
        }
        //PC问吧
        foreach ($list as $k=> $row)
        {
            $list[$k]['puttime'] = date('Y-m-d H:i:s', $row['puttime']);
            $list[$k]['commentatorId'] = $row['commentatorId']?$row['commentatorId']: '';
            $list[$k]['content'] = htmlspecialchars_decode($list[$k]['content'], ENT_QUOTES);
        }
        $list = $this->mergeNickName($list);
        $list = $this->mergeYidunInfo($list);
        $list = $this->addCommentatorIdUrl($list);
        return $list;
    }

    /**
     * loop list to get askId array
     * @param $list
     * @return array
     */
    public function getAskIdList($list)
    {
        $oids  = array_column($list, 'oid');
        $askIdMap= array_fill_keys($oids, '');
        $repository = $this->getAnswerRepository();
        $askIdList  = $repository->getAskIdList($oids);
        foreach ($askIdList as $row)
        {
            $askIdMap[$row['anid']] = $row['askId'];
        }
        return $askIdMap;
    }

    /**
     * get nickname and merge to list
     * @param $list
     * @return mixed
     */
    private function mergeNickName($list)
    {
        $uids  = array_column($list, 'uid');
        foreach ($uids as $k => $uid)
        {
            if (!$uid)
            {
                unset($uids[$k]);
            }
        }
        //昵称Map,保存用户ID与昵称之间的对应关系
        $nickNameMap = $this->getNickNameList($uids);
        foreach ($list as $k=> $row)
        {
            $list[$k]['nickName']= isset($nickNameMap[$row['uid']])?$nickNameMap[$row['uid']]:'';
        }
        return $list;
    }

    /**
     * get yidun info, and merge to list
     * @param $list
     * @return mixed
     */
    private function mergeYidunInfo($list)
    {
        $comtype = $list[0]['comtype'];
        $comIds  = array_column($list, 'comid');
        $yidunStatusNameMap = $this->getYidunStatusNameList($comtype, $comIds);
        foreach ($list as $k=> $row)
        {
            $list[$k]['yidunStatusName']= isset($yidunStatusNameMap[$row['comid']]['checkDes'])?$yidunStatusNameMap[$row['comid']]['checkDes']:'';
        }
        return $list;
    }

    /**
     * add commentatorIdUrl field, use for pc comment list
     * @param $list
     * @return mixed
     */
    private function addCommentatorIdUrl($list)
    {
        $comtype  = $list[0]['comtype'];
        $askIdMap = $this->getAskIdList($list);
        foreach ($list as $k=> $row)
        {
            $askId = isset($askIdMap[$row['oid']])?$askIdMap[$row['oid']]: '';
            $list[$k]['commentatorIdUrl'] = $this->getCommentIdUrl($comtype, $askId, $row['url']);
        }
        return $list;
    }

    /**
     * get pc comment total pages
     * @param $map
     * @return int
     */
    public function queryPageListCount($map)
    {
        return $this->getTo8toCommentRepository()->queryPageListCount($map);
    }

    /**
     * generate response data
     * @param Request $request
     * @param $isHidden
     * @return array
     */
    public function generateResponseData(Request $request, $isHidden)
    {
        $map = \CommonBundle\utils\RequestHelp::all($request);
        $page    = isset($map['page'])?(int)$map['page']: 1;
        $perPage = isset($map['perPage'])?(int)$map['perPage']: 10;
        $map['isHidden'] = $isHidden;
        $list = $this->getCommentPageResult($map, $perPage, $page);
        $total = $this->queryPageListCount($map);
        return [
            'list' => $list,
            'total'=> $total
        ];
    }

    /*
     * pass method, make pc comment status is pass status
     */
    public function passComment($comIds, $isHidden)
    {
        $repository = $this->getTo8toCommentRepository();
        if (is_string($comIds))
        {
            $comIds = (array)$comIds;
        }
        $isPass     = $repository->setPassStatus($comIds, $isHidden);
        return $isPass;
    }
}