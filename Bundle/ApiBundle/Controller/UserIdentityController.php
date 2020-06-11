<?php
/**
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 */
namespace ApiBundle\Controller;

use CommonBundle\Service\CmsUserIdentityService;


use CommonBundle\Utils\TimeHelp;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserIdentityController extends BaseController
{
    protected $userIdentityService;
    public function __construct(ContainerInterface $container, CmsUserIdentityService $userIdentifyService)
    {
        $this->container           = $container;
        $this->userIdentityService = $userIdentifyService;
    }

    /**
     * get user indentity list action 
     * @Route(name="user_identity_list", path="/user-identity/list", defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function userIdentityListAction()
    {
        $req      = $this->getRequest();
        $status   = $req->request->get('status', -1);
        if ($status === '')
        {
            $status = -1;
        }
        $uid      = (int)$req->request->get('uid')??'';
        $userName = $req->request->get('username')??'';
        $page     = (int)$req->request->get('page', 1);
        $pageSize = (int)$req->request->get('size')??10;
        $re       = $this->userIdentityService->getUserIdentityList($status, $uid, $userName, $page, $pageSize);
        $result   = $this->userIdentityService->getListAndOptions($re);
        if ($result['data'])
        {
            return $this->successJsonResponse($result['data'], $result['allRows']);
        }
        else
        {
            return $this->successJsonResponse($result['data'], $result['allRows'], 'no match record');
        }
    }

    /**
     * get user identity detail action
     * @Route(name="get_user_identity_detail", path="/user-identity/detail", defaults={"crossDomainAuth":true})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUserIdentityDetailAction()
    {
        $id   = (int)$this->getRequest()->get('id');
        $data = $this->userIdentityService->getUserIdentityDetail($id);
        if ($data)
        {
            $data['createTime'] = date('Y-m-d H:i:s', $data['createTime']);
            return $this->successJsonResponse($data, 1);
        }
        else
        {
            return $this->successJsonResponse($data, 0, 'no match record');
        }
    }

    /**
     * edit user identity info action
     * @Route(name="user_identity_edit", path="/user-identity/edit", defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function userIdentityEditAction()
    {
        $req      = $this->getRequest();
        $id       = $req->request->getInt('id')??0;
        $type     = $req->request->getInt('identityType')??0;
        $desc     = $req->request->get('identificationDesc')??'';
        $uid      = (int)$req->get('uid', 0);
        if (!$id)
        {
            $res = $this->userIdentityService->addUserIdentity($uid, $type, $desc);
            if (!$res)
            {
                if ($res === false)
                {
                    $msg = "输入的用户ID无效!";
                }
                else
                {
                    $msg = "当前用户ID的认证信息已经存在!";
                }
                return $this->faildJsonResponse([], 0, $msg);
            }
            else
            {
                return $this->successJsonResponse();
            }
        }
        else
        {
            $result = $this->userIdentityService->saveUserIdentity($id, $type, $desc);
            if ($result)
            {
                return $this->successJsonResponse();
            }
            else
            {
                return $this->faildJsonResponse();
            }
        }
    }


    /**
     * show or hidden user identity info action
     * @Route(name="show_user_identity", path="/user-identity/show", defaults={"crossDomainAuth":true})
     * @param Request $request
     * @param integer $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showUserIdentityAction(Request $request)
    {
        $status = (int)$request->get('identityStatus', 1);
        $id     = (int)$request->get('id', 1);
        $res    = $this->userIdentityService->showUserIdentity($id, $status);
        if ($res)
        {
            return $this->successJsonResponse();
        }
        else
        {
            return $this->faildJsonResponse();
        }
    }

    /**
     * del user identity info action
     * @Route(name="del_user_identity", path="/user-identity/delete", defaults={"crossDomainAuth":true})
     * @param integer $ids
     */
    public function delUserIdentityAction()
    {
        $id        = (int)$this->getRequest()->get('ids');
        if (!$id)
        {
            return $this->faildJsonResponse( [], 0, '参数非法!' );
        }
        $isSuccess = $this->userIdentityService->delUserIdentity($id);
        return $this->successJsonResponse();
    }


    /**
     * get user identity type list action
     * @Route(name="user_identity_type_list", path="/user-identity/type-list", defaults={"crossDomainAuth":true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUserIdentityTypeListAction()
    {
        $list = $this->userIdentityService->getUserIdentityTypeList();
        return $this->successJsonResponse([ 'list' => $list, 'option' => [] ], count($list));
    }

    /**
     * judge user is the identity user action
     * @Route(name="is_user_identity", path="/user-identity/is-identity-user", defaults={"crossDomain": true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getIsUserIdentityAction()
    {
        $id         = (int)$this->getRequest()->get('id');
        $isIdentity = $this->userIdentityService->getIsIdentity($id);
        return $this->successJsonResponse([
            'isIdentity' => $isIdentity
        ], 1);
    }

    /**
     * pass uids params, get user identity info list action, used for platform and system
     * @Route(name="get_identity_info_w", path="/user-identity/get-user-identity-info", defaults={"crossDomain": true})
     * @param $uids string 以逗号拼接的用户ID的字符串
     * @return JsonResponse
     */
    public function getIdentityInfoAction()
    {
        $uids   = (string)$this->getRequest()->get('uids');
        $uidArr = explode(',', $uids);
        if (!$uidArr || !$uids)
        {
            return $this->faildJsonResponse([ ], 0, 'param uids is empty!');
        }
        $re  = $this->userIdentityService->getUserIdentityListByUids($uidArr);
        return $this->successJsonResponse($re, count($uidArr));
    }


    /**
     * get user identity info action, use for app
     * @Route(name="user_identity_info", path="/user-identity/get-identity-info", defaults={"auth": true})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUserIdentityInfoAction()
    {
        $id           = (int) $this->getRequest()->get('id');
        $identityInfo = $this->userIdentityService->getUserIdentityList(1, $id, '');
        if (!$identityInfo['data'])
        {
            return $this->successJsonResponse([], 0);
        }
        $userIndentityInfo = $identityInfo['data'][0];
        $list         = $this->userIdentityService->getUserIdentityTypeList();
        $identityTypeMap   = [];
        $identityPicUrlMap = [];
        foreach ($list as $row)
        {
            $identityTypeMap[$row['identity_type']]   = $row['identity_name'];
            $identityPicUrlMap[$row['identity_type']] = $row['identity_pic_url'];
        }
        $type    = $userIndentityInfo['identityType'];
        $typeDescMap = $this->userIdentityService->getIdentityTypeDesc();
        $desc        = $typeDescMap[$type];
        $userIndentityInfo['createTime']       = date('Y年m月d日', $userIndentityInfo['createTime']);
        $userIndentityInfo['identityTypeDesc'] = $desc;
        $userIndentityInfo['identityPicUrl']   = isset($identityPicUrlMap[$userIndentityInfo['identityType']]) ? $identityPicUrlMap[$userIndentityInfo['identityType']]: '';
        $userIndentityInfo['identityTypeName']   = isset($identityTypeMap[$userIndentityInfo['identityType']]) ? $identityTypeMap[$userIndentityInfo['identityType']]: '';
        return $this->successJsonResponse($userIndentityInfo, 1);
    }

}
