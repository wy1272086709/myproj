<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */
namespace ApiBundle\Controller;

use CommonBundle\Service\CmsSocialAuditService;
use CommonBundle\Service\SocialService;
use CommonBundle\Utils\RequestHelp;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * ugc article and 
 * video audit controller
 */
class SocialAuditController extends BaseController
{
    protected $socialAuditService;
    protected $socialService;
    const NOPASS_STATUS   = 2;
    const PASS_STATUS     = 1;
    const WAIT_PASS_STATUS= 0;
    public function __construct(ContainerInterface $container,CmsSocialAuditService $service, SocialService $socialService)
    {
        $this->socialAuditService = $service;
        $this->socialService      = $socialService;
        $this->container          = $container;
    }

    /**
     * get article or video list action
     * @Route(name="audit_manage_list", path="/social-audit/manage/list", defaults={"crossDomainAuth": true})
     */
    public function auditManageListAction(Request $request)
    {
        // timeType 1为上传时间, 这里timeType固定写1
        list($page, $size, $baseType, $objectIdType, $timeType, $idText) = RequestHelp::getInstance()
        ->addIntOption('page')
        ->addIntOption('size')
        ->addIntOption('baseType')
        ->addIntOption('objectIdType')
        ->addIntOption('timeType')
        ->addStringNotRequireOption('idText')
        ->extractCheckOptions($request);
        if ($idText && (int)$idText === 0)
        {
            return $this->faildJsonResponse([], 0, '输入ID或者作者ID必须为数字!');
        }
        $status     = (int)$request->get('status');
        $beginTime  = $request->get('beginTime', '');
        $endTime    = $request->get('endTime',   '');
        $recommendType = $request->get('recommendType');
        $passType      = (int)$request->get('passType');
        $queryParams = [
            'page' => $page,
            'size' => $size,
            'timeType' => $timeType,
            'baseType' => $baseType,
            'objectIdType'  => $objectIdType,
            'idText'        => $idText,
            'recommendType' => $recommendType,
        ];
        if ($beginTime && $endTime)
        {
            $queryParams['beginTime'] = $beginTime;
            $queryParams['endTime']   = $endTime;
        }
        $passType> 0 && $queryParams['passType'] = $passType;
        $res = $this->socialAuditService->getSocialAuditList($queryParams, $status);
        $total = $this->socialAuditService->getSocialAuditCount($queryParams, $status);
        return $this->successJsonResponse($res, (int)$total);
    }

    /**
     * get ugc detail action
     * @Route(name="view_social", path="/social-audit/view", defaults={"crossDomain": true})
     */
    public function viewSocialAction(Request $request)
    {
        $id       = $request->get('id');
        $detail   = $this->socialAuditService->ugcDetail($id);
        return $this->successJsonResponse($detail , 1);
    }


    /**
     * ugc audit pass action 
     * @Route(name="audit_social", path="/social-audit/pass", defaults={"crossDomainAuth": true})
     * @param manualStatus
     * @param rejectReason
     * @param auditorId
     * @param baseIdArr
     */
    public function auditPassAction(Request $request)
    {
        $reqParams = $request->request->all();
        if (!empty($reqParams['rejectReason']) && mb_strlen($reqParams['rejectReason'], 'UTF-8')> 128 )
        {
            return $this->faildJsonResponse([], 0, '审核不通过的原因不超过128的字符长度!');
        } else if (empty($reqParams['rejectReason']) && $reqParams['manualStatus'] == 0) {
            return $this->faildJsonResponse([], 0, '审核不通过的原因必须!');
        }
        $res       = $this->socialAuditService->dealSocialStatus($reqParams);
        if ($res)
        {
            return $this->successJsonResponse([], 0);
        }
        else
        {
            return $this->faildJsonResponse([], 0);
        }
    }

    /**
     * ugc recomment action
     * @Route(name="recommend_social", path="/social-audit/recommend", defaults={"crossDomainAuth": true})
     * @param Request $request
     * @param recommendType
     * @param recommendTime (代码添加)
     * @param recommenderId
     * @param recommenderName
     * @param baseIdArr
     */
    public function recommendAction(Request $request)
    {
        list($recommenderId, $recommenderName) = RequestHelp::getInstance()
            ->addIntOption('recommenderId')
            ->addStringOption('recommenderName')
            ->extractCheckOptions($request);
        $recommendType = (int) $request->get('recommendType');
        $reqParams = [
            'recommendType'   => $recommendType,
            'recommenderId'   => $recommenderId,
            'recommenderName' => $recommenderName
        ];
        $reqParams['baseIdArr']     = $request->get('baseIdArr');
        $reqParams['recommendTime'] = time();
        $res       = $this->socialAuditService->dealSocialRecommend($reqParams);
        if ($res)
        {
            return $this->successJsonResponse([], 0);
        }
        else
        {
            return $this->faildJsonResponse([], 0);
        }
    }

}
