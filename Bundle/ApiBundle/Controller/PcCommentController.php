<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */
namespace ApiBundle\Controller;
use CommonBundle\Service\To8toCommentService;
use CommonBundle\Utils\RequestHelp;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * pc comment module controller
 */
class PcCommentController extends BaseController
{
    protected $to8toCommentService;
    const COMMENT_PASS_STATUS    = 0;
    const COMMENT_NOPASS_STATUS  = 1;
    const COMMENT_NOAUDIT_STATUS = 2;
    // inject service
    public function __construct(To8toCommentService $service)
    {
        $this->to8toCommentService = $service;
    }

    /**
     * get pc comment pass list action
     * @Route(name="pc_comment_pass_list", path="/comment-pass", defaults={"crossDomainAuth": true})
     */
    public function passListAction(Request $request)
    {
        return $this->listResponseHelp($request, self::COMMENT_PASS_STATUS);
    }

    /**
     * get pc comment nopass list action
     * @Route(name="pc_comment_nopass_list", path="/comment-nopass", defaults={"crossDomainAuth": true})
     */
    public function nopassListAction(Request $request)
    {
        return $this->listResponseHelp($request, self::COMMENT_NOPASS_STATUS);
    }

    /**
     * get pc comment noaudit list action
     * @Route(name="pc_comment_noaudit_list", path="/comment-noaudit", defaults={"crossDomainAuth": true})
     */
    public function noAuditListAction(Request $request)
    {
        return $this->listResponseHelp($request, self::COMMENT_NOAUDIT_STATUS);
    }

    /**
     * pc comment audit action 
     * @Route(name="pc_comment_audit", path="/comment/audit", defaults={"crossDomainAuth": true})
     */
    public function commentAuditAction(Request $request)
    {
        $data   = RequestHelp::all($request);
        $comIds = isset($data['comid'])?$data['comid']: [];
        $isHidden = isset($data['ishidden'])? (int)$data['ishidden']: '';
        $res = $this->to8toCommentService->passComment($comIds, $isHidden);
        if ($res)
        {
            return $this->successJsonResponse([], 0, '更新评论状态成功!');
        }
        else
        {
            return $this->faildJsonResponse([], 0);
        }
    }

    protected function listResponseHelp(Request $request, $isHidden)
    {
        $res = $this->to8toCommentService->generateResponseData($request, $isHidden);
        $total = $res['total'];
        return $this->successJsonResponse($res['list'], $total);
    }
}