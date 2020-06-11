<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */

namespace ApiBundle\Controller;

use ApiBundle\Form\SpecialTopicForm;
use CommonBundle\Service\SpecialTopicService;
use Symfony\Component\Routing\Annotation\Route;
use CommonBundle\Utils\ErrorCode;


class SpecialTopicController extends BaseController
{

    /**
     * @var SpecialTopicForm
     */
    protected $form = null;
    protected $specialTopicService = null;

    public function __construct(SpecialTopicService $specialTopicService)
    {
        $this->specialTopicService = $specialTopicService;
    }

    /**
     * @return SpecialTopicForm
     */
    public function setForm()
    {
        $this->form = new SpecialTopicForm($this->getRequest());

        return $this->form;
    }

    /**
     * special topic agg action 
     * @Route("/specialTopic/agg",name="special_topic_agg",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function aggAction()
    {
        $params = $this->setForm()->getAggParams();
        $data = $this->specialTopicService->aggSpecailTopic($params);
        if (empty($data)) {
            return $this->faildJsonResponse([], 0, ErrorCode::getErrorMsg(ErrorCode::NO_RECORD), ErrorCode::NO_RECORD);
        }

        return $this->successJsonResponse($data, 1);
    }

    /**
     * special topic detail action
     * @Route("/specialTopic/detail",name="special_topic_detail",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function detailAction()
    {
        $params = $this->setForm()->getDetailParams();
        $data = $this->specialTopicService->getSpecailTopicDetail($params);
        if (empty($data)) {
            return $this->faildJsonResponse([], 0, ErrorCode::getErrorMsg(ErrorCode::NO_RECORD), ErrorCode::NO_RECORD);
        }

        return $this->successJsonResponse($data, 1);
    }

    /**
     * @Route("/specialTopic/latest",name="special_topic_latest",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function latestAction()
    {
        $params = $this->setForm()->getLatestParams();
        $data = $this->specialTopicService->getLatestSpecialTopic($params);

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
     * @Route("/specialTopic/search",name="special_topic_search",defaults={"crossDomain":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function searchAction()
    {
        $params = $this->setForm()->getSearchParams();
        $resp = $this->specialTopicService->searchSpecialTopic($params);
        if (empty($resp)) {
            return $this->faildJsonResponse([], 0, ErrorCode::getErrorMsg(ErrorCode::NO_RECORD), ErrorCode::NO_RECORD);
        }

        return $this->successJsonResponse($resp['data'], $resp['allRows']);
    }


    /**
     * @Route("/specialTopic/checkContent",name="special_topic_check_content",defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function checkContentAction()
    {
        $params = $this->setForm()->getCheckContentParams();
        $resp = $this->specialTopicService->checkContent($params);
        if (empty($resp)) {
            return $this->faildJsonResponse([], 0, ErrorCode::getErrorMsg(ErrorCode::NO_RECORD), ErrorCode::NO_RECORD);
        }

        return $this->successJsonResponse($resp);
    }


    /**
     * @Route("/specialTopic/add",name="special_topic_add",defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function addAction()
    {
        $params = $this->setForm()->getAddParams();
        $data = $this->specialTopicService->addSpecialTopic($params);

        if (empty($data)) {
            return $this->faildJsonResponse([]);
        }

        return $this->successJsonResponse($data);

    }


    /**
     * @Route("/specialTopic/edit",name="special_topic_edit",defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function editAction()
    {
        $params = $this->setForm()->getEditParams();
        $data = $this->specialTopicService->editSpecialTopic($params);

        if (empty($data)) {
            return $this->faildJsonResponse([]);
        }

        return $this->successJsonResponse($data);

    }


    /**
     * @Route("/specialTopic/recommend",name="special_topic_recommend",defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function recommendAction()
    {

        $params = $this->setForm()->getIdParams();
        $data = $this->specialTopicService->recomendSpecialTopic($params);

        if (empty($data)) {
            return $this->faildJsonResponse([]);
        }

        return $this->successJsonResponse([]);

    }


    /**
     * @Route("/specialTopic/cancelRecommend",name="special_topic_cancel_recommend",defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function cancelRecommendAction()
    {
        $params = $this->setForm()->getIdParams();
        $data = $this->specialTopicService->cancelRecomendSpecialTopic($params);

        if (empty($data)) {
            return $this->faildJsonResponse([]);
        }

        return $this->successJsonResponse();

    }


    /**
     * @Route("/specialTopic/publish",name="special_topic_publish",defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function publishAction()
    {

        $params = $this->setForm()->getPublishParams();
        $data = $this->specialTopicService->publishSpecialTopic($params);

        if (empty($data)) {
            return $this->faildJsonResponse([]);
        }

        return $this->successJsonResponse();

    }

    /**
     * @Route("/specialTopic/cancelPublish",name="special_topic_cancel_publish",defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function cancelPublishAction()
    {

        $params = $this->setForm()->getIdParams();
        $data = $this->specialTopicService->cancelPublishSpecialTopic($params);

        if (empty($data)) {
            return $this->faildJsonResponse([]);
        }

        return $this->successJsonResponse();

    }
}