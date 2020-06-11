<?php
/**
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 */
namespace ApiBundle\Controller;

use ApiBundle\Form\XgtForm;
use CommonBundle\Service\To8toXiaoguotuService;
use CommonBundle\Service\XgtService;
use CommonBundle\Utils\ErrorCode;
use Symfony\Component\Routing\Annotation\Route;


class XgtController extends BaseController
{

    /**
     * @var null
     */
    protected $xgtService = null;

    public function __construct(XgtService $xgtService)
    {
        $this->xgtService = $xgtService;
    }

    /**
     * xgt export action, old use
     * @Route("/xgt/search",name="xgt_search",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function search()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getSearchParams();
        $page = $form->getPage();
        $size = $form->getSize();
        $result = $this->xgtService->searchByCondition($params, $page, $size);
        if (empty($result)) {
            $errcode = ErrorCode::NO_RECORD;

            return $this->successJsonResponse([], 0, ErrorCode::getErrorMsg($errcode), $errcode);
        }

        return $this->successJsonResponse($result['data'], $result['allRows']);
    }

    /**
     * xgt export action new use
     * @Route("/xgt/search-export",name="xgt_search_export",defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function searchnew()
    {
        set_time_limit(0);
        $form = new XgtForm($this->getRequest());
        $params = $form->getSearchParams();
        $xiaoguotuService = $this->container->get('to8to.xiaoguotu');
        $total = 0;
        if ($xiaoguotuService instanceof To8toXiaoguotuService)
        {
            $result = $xiaoguotuService->getXgtListForExport($params);
            if ($result)
            {
                $total = 1;
            }
            else
            {
                $total = 0;
            }
        }
        if (empty($result)) {
            $errcode = ErrorCode::NO_RECORD;
            return $this->successJsonResponse([], 0, ErrorCode::getErrorMsg($errcode), $errcode);
        }

        return $this->successJsonResponse($result, $total);
    }


    /**
     * @Route("/xgt/recommendPcXgt",name="xgt_recommend_pcxgt",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function recommendToPcXgt()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getStatusParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->recommendToPcXgt($params['oldaid']);

        return $this->isSuccessResult($result);

    }

    /**
     * @Route("/xgt/recommendAppFeed",name="xgt_recommend_app_feed",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function recommendToAppFeed()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getStatusParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->recommendToAppFeed($params['oldaid']);

        return $this->isSuccessResult($result);

    }

    /**
     * @Route("/xgt/recommendCityIndex",name="xgt_recommend_city_idx",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function recommendToCityIndex()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getOldCidParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->recommendToCityIndex($params['oldcid']);

        return $this->isSuccessResult($result);

    }

    /**
     * @Route("/xgt/cancelRecommendPcXgt",name="xgt_cancel_recommend_pcxgt",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function cancelRecommendToPcXgt()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getStatusParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->cancelRecommendToPcXgt($params['oldaid']);

        return $this->isSuccessResult($result);

    }

    /**
     * @Route("/xgt/cancelRecommendAppFeed",name="xgt_cancel_recommend_app_feed",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function cancelRecommendToAppFeed()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getStatusParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->cancelRecommendToAppFeed($params['oldaid']);

        return $this->isSuccessResult($result);

    }

    /**
     * @Route("/xgt/cancelRecommendCityIndex",name="xgt_cancel_recommend_city_idx",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function cancelRecommendToCityIndex()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getOldCidParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->cancelRecommendToCityIndex($params['oldcid']);

        return $this->isSuccessResult($result);

    }

    /**
     * @Route("/xgt/setVisible",name="xgt_set_visible",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function changeToVisibleStatus()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getStatusParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->changeToVisibleStatus($params['oldaid'], $params['startTime']);

        return $this->isSuccessResult($result);
    }

    /**
     * @Route("/xgt/setHiddent",name="xgt_set_hiddent",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function changeToHiddenStatus()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getStatusParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->changeToHiddenStatus($params['oldaid'], $params['startTime']);

        return $this->isSuccessResult($result);

    }

    /**
     * @Route("/xgt/setUrlStatus",name="xgt_set_url_status",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function changeToUrlStatus()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getStatusParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->changeToUrlStatus($params['oldaid'], $params['startTime']);

        return $this->isSuccessResult($result);

    }

    /**
     * @Route("/xgt/setWaitVisible",name="xgt_set_wait_visible",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function changeToWaitVisibleStatus()
    {

        $form = new XgtForm($this->getRequest());
        $params = $form->getStatusParams();
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $result = $service->changeToWaitVisibleStatus($params['oldaid'], $params['startTime']);

        return $this->isSuccessResult($result);
    }

    /**
     * @Route("/xgt/edit",name="xgt_edit",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function edit()
    {
        $form = new XgtForm($this->getRequest());
        $params = $form->getEditForm();
        /**
         * @var  XgtService $service
         */
        if (!empty($params['title']) && mb_strlen($params['title'], 'UTF-8')>50) {
            return $this->faildJsonResponse([], 0, '标题长度不超过50个字符长度!');
        }
        $service = $this->container->get(XgtService::class);
        $result = $service->edit($params);

        if (empty($result)) {
            return $this->faildJsonResponse();
        }

        return $this->successJsonResponse();

    }

    /**
     * @Route("/xgt/get/config",name="xgt_get_config",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function getConditionList()
    {
        /**
         * @var  XgtService $service
         */
        $service = $this->container->get(XgtService::class);
        $data = $service->getConditionList();
        if (empty($data)) {
            return $this->faildJsonResponse([], 0, ErrorCode::getErrorMsg(ErrorCode::NO_RECORD), ErrorCode::NO_RECORD);
        }

        return $this->successJsonResponse($data);

    }


}
