<?php
/**
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 */
namespace ApiBundle\Controller;

use CommonBundle\Service\UgcService;
use CommonBundle\Utils\ErrorCode;
use CommonBundle\Utils\RequestHelp;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UgcController
 * @package ApiBundle\Controller
 */
class UgcController extends BaseController
{

    protected $ugcService;

    /**
     * UgcController constructor.
     * @param ContainerInterface $container
     * @param UgcService $ugcService
     */
    public function __construct(ContainerInterface $container, UgcService $ugcService)
    {
        $this->setContainer($container);
        $this->ugcService = $ugcService;
    }

    /**
     * ugc get video detail action 
     * @Route("/social/video/detail", name="social_video_detail", defaults={"crossDomain":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function socialVideoDetailAction(Request $request)
    {
        $id = intval($request->get('id'));
        $uid = intval($request->get('uid'));
        $appId = intval($request->get('appid'));
        $token = $request->get('to8to_token', '');
        if ($uid) {
            $this->checkToken($token, $appId, $uid);
        }
        $data = $this->ugcService->videoDetail($id, $uid);
        if (empty($data)) {
            return $this->faildJsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::PAGE_NOT_FOUND),
                ErrorCode::PAGE_NOT_FOUND
            );
        }
        return $this->successJsonResponse($data);
    }

    /**
     * ugc get article detail action
     * @Route("/social/article/detail", name="social_article_detail", defaults={"crossDomain":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function socialArticleDetailAction(Request $request)
    {
        $id = intval($request->get('id'));
        $uid = intval($request->get('uid'));
        $appId = intval($request->get('appid'));
        $token = $request->get('to8to_token', '');
        if ($uid) {
            $this->checkToken($token, $appId, $uid);
        }
        $data = $this->ugcService->articleDetail($id, $uid);
        if (empty($data)) {
            return $this->faildJsonResponse(
                [],
                0,
                ErrorCode::getErrorMsg(ErrorCode::PAGE_NOT_FOUND),
                ErrorCode::PAGE_NOT_FOUND
            );
        }
        return $this->successJsonResponse($data);
    }

    /**
     * ugc get article or video list action
     * @Route("/social/ugc/list", name="social_ugc_list", defaults={"crossDomain":true})
     * @param Request $request 请求体
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function socialUgcListAction(Request $request)
    {
        $uid = intval($request->get('uid'));
        $appId = intval($request->get('appid'));
        $targetUid = intval($request->get('target_uid'));
        $type = intval($request->get('type', 1));
        $page = intval($request->get('page', 1));
        $pageSize = intval($request->get('pageSize', 20));
        $token = $request->get('to8to_token', '');
        if ($uid) {
            $this->checkToken($token, $appId, $uid);
        }
        $data = $this->ugcService->getUgcListByUid($targetUid, $page, $pageSize, $type, $uid);
        return $this->successJsonResponse($data);
    }
}
