<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */

namespace ApiBundle\Controller;


use ApiBundle\Form\SocialForm;
use CommonBundle\Service\CmsSocialImgTagMapService;
use CommonBundle\Service\OssService;
use CommonBundle\Service\SocialService;
use CommonBundle\Utils\ErrorCode;
use CommonBundle\Utils\RequestHelp;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use CommonBundle\Service\SecurityService;
use CommonBundle\Service\UserService;
use CommonBundle\Utils\ExceptionHelp;

class SocialController extends BaseController
{

    protected $socialService = null;
    protected $ossService = null;
    protected $reqArr = null;
    protected $errorCodeArr = null;

    public function __construct(ContainerInterface $container, SocialService $service, OssService $ossService)
    {
        $this->container = $container;
        $this->socialService = $service;
        $this->ossService = $ossService;
    }


    /**
     * user publish meitu action, use for app users
     * @Route("/social/meitu/publish",name="publish_meitu",defaults={"auth":true,"crossDomain":true, "rateLimiter":{"module":"social:publish:meitu:","uid":{1, 10}}})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @throws \Doctrine\DBAL\ConnectionException
     *
     *
     * @throws \Exception
     *
     */
    public function publishMeituAction()
    {
        $form = new SocialForm($this->getRequest());
        $params = $form->getAddParams();

        if ($data = $this->socialService->addMeitu($params)) {
            return $this->successJsonResponse($data);
        }

        return $this->faildJsonResponse();

    }


    /**
     *
     * get meitu detail action 
     * @Route("/social/meitu/detail",name="social_meitu_detail",defaults={"auth":false,"crossDomain":true})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @throws \Exception
     */
    public function getMeituDetailAction()
    {
        //get the params from request
        $form = new SocialForm($this->getRequest());
        $params = $form->getDetailParams();
        //get match data from db
        $detailInfo = $this->socialService->getMeituDetail($params);

        if (empty($detailInfo)) {
            return $this->faildJsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::PAGE_NOT_FOUND),
                ErrorCode::PAGE_NOT_FOUND
            );
        }
        return $this->successJsonResponse($detailInfo);
    }

    /**
     * get user latest meitu action
     *
     * @Route("/social/userMeitu/latest",name="social_user_meitu_latest",defaults={"auth":true,"crossDomain":true})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @throws \Exception
     */
    public function getUserLatestMeituAction()
    {

        //get the params from request
        $form = new SocialForm($this->getRequest());
        $params = $form->getUserLatestMeituParams();
        //get match data from db
        $detailInfo = $this->container->get(CmsSocialImgTagMapService::class)->getUserLatestTagMeitu($params);

        if (empty($detailInfo)) {
            return $this->faildJsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::PAGE_NOT_FOUND),
                ErrorCode::PAGE_NOT_FOUND
            );
        }

        return $this->successJsonResponse($detailInfo);

    }


    /**
     * get user meitu list action
     * @Route("/social/my/meitu/list", name="social_my_meitu_list", defaults={"auth":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\ORMException
     */
    public function getUsersMeituAction(Request $request)
    {
        $this->reqArr['page'] = (int)$request->get('page') > 0 ? (int)$request->get('page') : 1;
        $this->reqArr['perPage'] = (int)$request->get('perPage') > 0 ? (int)$request->get('perPage') : 10;
        $this->reqArr['uid'] = (int)$request->get('uid');
        $res = $this->socialService->getUsersMeitu(
            $this->reqArr['uid'],
            $this->reqArr['page'],
            $this->reqArr['perPage'],
            0
        );

        return $this->jsonResponse($res['data'], $res['count']);
    }

    /**
     * batch operate, delete mcn data action 
     * @Route("/social/del", name="social_del", defaults={"auth":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delSocialDataAction(Request $request)
    {
        $this->reqArr['socialIds'] = $request->get('socialIds');
        //socialType只是用于查看接口请求是删除哪种类型的内容
        $this->reqArr['socialType'] = (int)$request->get('socialType');
        $this->reqArr['uid'] = (int)$request->get('uid');

        $socialIdArr = explode(',', $this->reqArr['socialIds']);
        empty($socialIdArr) && $this->jsonResponse(
            [],
            0,
            ErrorCode::getErrorMsg(ErrorCode::EMPTY_PARAMS),
            ErrorCode::EMPTY_PARAMS
        );

        $dealRes = $this->socialService->delSocialData($socialIdArr, $this->reqArr['uid']);

        return $dealRes ?
            $this->jsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::OPERATION_SUCCESS),
                ErrorCode::OPERATION_SUCCESS
            ) :
            $this->jsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::OPERATION_FAILED),
                ErrorCode::OPERATION_FAILED
            );
    }

    /**
     * cms query content list action
     * @Route("/social/cms/list", name="social_cms_list", defaults={"crossDomainAuth":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\ORMException
     */
    public function socialListAction(Request $request)
    {
        $this->reqArr['baseType'] = (int)$request->get('baseType', 1);
        if ($this->reqArr['baseType'] === 0)
        {
            $this->reqArr['baseType'] = 1;
        }
        $this->reqArr['status'] = (int)$request->get('status', -1);
        $this->reqArr['objectIdType'] = (int)$request->get('objectIdType', 0);
        $this->reqArr['objectId'] = (int)$request->get('objectId', 0);
        $this->reqArr['passType'] = (int)$request->get('passType', -1);
        $this->reqArr['noPassType'] = (int)$request->get('noPassType', -1);
        $this->reqArr['recommendType'] = (int)$request->get('recommendType', -1);
        $this->reqArr['page'] = (int)$request->get('page', 1);
        $this->reqArr['size'] = (int)$request->get('size', 10);
        $this->reqArr['timeSection'] = $this->filterStr($request->get('timeString'));

        $listInfo = $this->socialService->getSocialList($this->reqArr);

        return $this->jsonResponse($listInfo['data'], $listInfo['count']);
    }

    /**
     * recommend or cancel recommend action
     * @Route("/social/cms/recommend", name="social_cms_recommend", defaults={"crossDomainAuth":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\ORMException
     */
    public function socialRecommendAction(Request $request)
    {
        $this->reqArr['baseIdArr'] = explode(',', $request->get('baseIds', ''));
        $this->reqArr['recommendType'] = (int)$request->get('recomType', 0);
        $this->reqArr['recommenderId'] = (int)$request->get('operateId', 0);
        $this->reqArr['recommenderName'] = $this->filterStr($request->get('operateName', ''));

        $res = $this->socialService->dealSocialRecommend($this->reqArr);

        return $res ?
            $this->jsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::OPERATION_SUCCESS),
                ErrorCode::OPERATION_SUCCESS
            ) :
            $this->jsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::OPERATION_FAILED),
                ErrorCode::OPERATION_FAILED
            );
    }

    /**
     * mcn use, audit mcn content, such as article,video, or picture
     * @Route("/social/cms/audit", name="social_cms_audit", defaults={"crossDomainAuth":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\ORMException
     */
    public function socialStatusAction(Request $request)
    {
        $this->reqArr['baseIdArr'] = explode(',', $request->get('baseIds', ''));
        $this->reqArr['manualStatus'] = (int)$request->get('manualStatus', 0);
        $this->reqArr['rejectReason'] = $this->filterStr($request->get('rejectReason', ''));
        $this->reqArr['auditorId'] = (int)$request->get('operateId', 0);
        $this->reqArr['auditorName'] = $this->filterStr($request->get('operateName', ''));

        if ($this->reqArr['manualStatus'] == 0 && empty($this->reqArr['rejectReason'])) {
            return $this->jsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::EMPTY_REASON),
                ErrorCode::EMPTY_REASON
            );
        }

        $res = $this->socialService->dealSocialStatus($this->reqArr);

        return $res ?
            $this->jsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::OPERATION_SUCCESS),
                ErrorCode::OPERATION_SUCCESS
            ) :
            $this->jsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::OPERATION_FAILED),
                ErrorCode::OPERATION_FAILED
            );
    }

    /**
     * mcn use, query content list
     * @Route("/mcn/social/cms/list", name="mcn_social_cms_list", defaults={"crossDomain":true})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function mcnSocialListAction(Request $request)
    {
        $this->checkTicketByUid();
        $params = RequestHelp::getInstance()
            ->addIntNotRequire('baseType')
            ->addStringNotRequireOption('status')
            ->addStringNotRequireOption('startDate')
            ->addStringNotRequireOption('endDate')
            ->addStringNotRequireOption('keyword')
            ->addIntOption('uid')
            ->addIntOption('page')
            ->addIntOption('pageSize')
            ->checkOptions($request);
        $params['authorId'] = $params['uid'] ?? 0;
        $params['baseType'] = $params['baseType'] ?? -1;
        $params['status'] = $params['status'] ?? -1;
        $data = $this->socialService->getMcnUgcList($params);
        $allRows = 0;
        if (isset($data['allRows'])) {
            $allRows = $data['allRows'];
            unset($data['allRows']);
        }
        return $this->successJsonResponse($data, $allRows);
    }

    /**
     * delete mcn action 
     * @Route("/mcn/social/cms/delete", name="mcn_social_cms_delete", defaults={"crossDomain":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function mcnSocialDeleteAction(Request $request)
    {
        $this->checkTicketByUid();
        $params = RequestHelp::getInstance()
            ->addIntOption('uid')
            ->addIntOption('id')
            ->checkOptions($request);
        $result = $this->socialService->deleteUgc($params['id'], $params['uid']);
        if ($result) {
            return $this->successJsonResponse();
        }
        return $this->faildJsonResponse();
    }

    /**
     * mcn push content action
     * @Route("/mcn/social/cms/save", name="mcn_social_cms_save", defaults={"crossDomain":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function mcnSaveAction(Request $request)
    {
        $this->checkTicketByUid();
        $params = RequestHelp::all($request);
        $params['authorId'] = $params['uid'] ?? 0;
        $data = $this->socialService->mcnSave($params);
        return !empty($data['baseId']) ? $this->successJsonResponse($data) :
            $this->faildJsonResponse([], 0, $data['errorMsg'] ?? '未知错误');
    }

    /**
     * ugc mcn detail action 
     * @Route("/mcn/social/cms/detail", name="mcn_social_cms_detail", defaults={"crossDomain":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function mcnDetailAction(Request $request)
    {
        $this->checkTicketByUid();
        $params = RequestHelp::getInstance()
            ->addIntOption('uid')
            ->addIntOption('id')
            ->checkOptions($request);
        $data = $this->socialService->ugcDetail($params['id'], $params['uid']);
        return !empty($data['id']) ? $this->successJsonResponse($data) :
            $this->faildJsonResponse([], 0, $data['errorMsg'] ?? '未知错误');
    }

    /**
     * get ossToken action 
     * @Route("/mcn/token/get", name="mcn_token_get", defaults={"crossDomain":true})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getOssTokenAction(Request $request)
    {
        $this->checkTicketByUid();
        $uid = (int) $request->get('uid');
        $data = $this->ossService->assumeRole($uid);
        if ($data && !empty($data['Credentials'])) {
            return $this->successJsonResponse($data);
        }
        return $this->faildJsonResponse([], 0, $data['Message'] ?? 'failed');
    }

    /**
     * accept aliyun callback action
     * @Route("/mcn/notifications", name="mcn_notifications", defaults={"crossDomain":true})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function mcnNotificationsAction(Request $request)
    {
        //todo 校验请求正确性
        $params = RequestHelp::all($request);
        $result = $this->ossService->videoCallBack($params);
        if ($result) {
            return $this->successJsonResponse();
        }
        return $this->faildJsonResponse();
    }

    /**
     * validate and check ticket
     * @param int $accountId
     * @param string $ticket
     * @param string $appName
     * @return bool
     */
    protected function checkTicket($accountId, $ticket, $appName = 'to8to_pc')
    {
        $securityService = $this->container->get(SecurityService::class);
        $result = $securityService->checkTicket($accountId, $ticket, $appName);
        if (!$result) {
            ExceptionHelp::throwApiProblemException(ErrorCode::USER_LOGIN_FAILED);
        }
        return true;
    }

    /**
     * get account_id ,params uid
     * @param int $uid
     * @return int
     */
    protected function getAccountIdByUid($uid = 0)
    {
        if ($uid <=0 ) {
            return 0;
        }
        $redisKey = "app:user:accountId";
        $redisService = $this->container->get('snc_redis.default');
        $accountId = $redisService->hGet($redisKey, $uid);
        if (!$accountId) {
            $userService = $this->container->get(UserService::class);
            $result = $userService->batchGetUserInfo([$uid]);
            $accountId = $result[$uid]['accountId'] ?? 0;
            $accountId > 0 && $redisService->hSet($redisKey, $uid, $accountId);
        }
        return $accountId;
    }

    protected function checkTicketByUid()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $params = RequestHelp::all($request);
        $uid = $params['uid'] ?? 0;
        $ticket = $params['ticket'] ?? '';
        $appName = $params['appName'] ?? 'to8to-pc';
        if (!$uid || !$ticket) {
            ExceptionHelp::throwApiProblemException(ErrorCode::USER_LOGIN_FAILED);
        }
        $accountId = $this->getAccountIdByUid($uid);
        $securityService = $this->container->get(SecurityService::class);
        $result = $securityService->checkTicketByAccountId($accountId, $ticket, $appName);
        if (!$result) {
            ExceptionHelp::throwApiProblemException(ErrorCode::USER_LOGIN_FAILED);
        }
        return true;
    }
}
