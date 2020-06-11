<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */
namespace ApiBundle\Controller;

use CommonBundle\Service\SecurityService;
use CommonBundle\Service\UserService;
use CommonBundle\Utils\ExceptionHelp;
use CommonBundle\Utils\RequestHelp;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use CommonBundle\Service\ApiTokenService;
use CommonBundle\Utils\ErrorCode;

class BaseController extends Controller
{
    protected $uid = 0;
    protected $to8toToken = '';
    protected $to8toAuth = '';
    //请求来源平台: 1 APP[默认]； 2 PC； 3 图满意； 4 微信
    protected $platform = 0;
    //回调 JS 方法名
    protected $apiCallback = '';
    //兼容原lotus框架中的$model参数(原基于lotus框架的api依靠$model和$action参数确定Action类)
    protected $apiModel = '';
    //兼容原lotus框架中的$action参数
    protected $apiAction = '';

    //移动设备的imei号码（一般是唯一的）
    protected $iMei = '';
    //客户端应用版本号
    protected $appVersion = '';
    protected $appId = 0;
    //app客户端类型: 1 安卓;  2 IOS
    protected $appOsType = 0;
    //apk包名如com.to8to.housekeeper
    protected $apkPackageName = '';
    //app客户端系统版本名称如com.to8to.housekeeper
    protected $systemVersion = '';
    //客户端渠道如苹果应用商店、腾讯应用宝、91手机助手等
    protected $channel = '';

    //Api版本号(服务端接口的版本号)
    protected $apiVersion = '2.5';

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Request
     *
     */
    public function getRequest()
    {
        return $this->get('request_stack')->getCurrentRequest();
    }


    /**
     * 初始化共用请求参数
     */
    public function initRequest()
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $this->uid = (int)$request->get('uid', 0);
        $this->to8toToken = $request->get('to8to_token', '');
        $this->to8toAuth = $request->get('to8to_auth', '');
        $this->platform = (int)$request->get('platForm', 1);
        $this->apiCallback = $request->get('callback', 'callback');
        $this->apiModel = $request->get('model', '');
        $this->apiAction = $request->get('action', '');

        $this->iMei = $request->get('imei', '');
        $this->appVersion = $request->get('appversion', '');
        $this->appId = (int)$request->get('appid', 0);
        $this->appOsType = (int)$request->get('appostype', 0);
        $this->apkPackageName = $request->get('apkPackageName', '');
        $this->systemVersion = $request->get('systemversion', '');
        $this->channel = $request->get('channel', '');
        $this->apiVersion = $request->get('version', '');
    }

    /**
     * 获取缓存服务对象
     *
     * (1)如果是在当前Controller内部调用则可以不传参数，使用默认参数值，假设当前Controller为thisController
     * $this->getCache()->set('UserName', 'reed.chen');
     * $this->getCache()->get('UserName');
     * (2)如果是跨Controller调用则需要设置参数
     * $thatController->getCache(thisController::class)->get('UserName');
     *
     * @param string $prefix 缓存key前缀
     * @return object
     */
    public function getCache($prefix = '')
    {
        if (!empty($prefix)) {
            $this->container->get('to8to.cache.cacheservice')->setPrefix($prefix);
        } else {
            $this->container->get('to8to.cache.cacheservice')->setPrefix(static::class);
        }

        return $this->container->get('to8to.cache.cacheservice');
    }

    /**
     * [简易]接口返回数据格式封装
     * @param array|object $data      接口数据域
     * @param int          $allRows   总记录数
     * @param string       $errorMsg  接口响应信息
     * @param int          $errorCode 接口错误码 正常情况返回0 兼容老的mobileapi接口
     * @return JsonResponse
     */
    public function jsonResponse($data, $allRows = 0, $errorMsg = 'success', $errorCode = 0)
    {
        return new JsonResponse(
            [
                'errorCode' => $errorCode,
                'errorMsg'  => $errorMsg,
                'allRows'   => $allRows,
                'data'      => $data
            ]
        );
    }

    public function successJsonResponse($data = [], $allRows = 0, $errorMsg = 'success', $errorCode = 0)
    {
        return $this->jsonResponse($data, $allRows, $errorMsg, $errorCode);
    }

    public function faildJsonResponse($data = [], $allRows = 0, $errorMsg = 'failed', $errorCode = 500)
    {
        return $this->jsonResponse($data, $allRows, $errorMsg, $errorCode);
    }

    /**
     * 校验token数据
     * 参数是to8toToken,appId
     * 依赖于CommonBundle\Service\ApiTokenService
     * return void
     */
    public function checkUserToken()
    {
        if (!$this->to8toToken) {
            exit($this->jsonResponse([], 0, '用户凭证缺失', 10007));
        }

        $checkTokenRes = $this->container->get('to8to.api.token')->checkToken($this->to8toToken, $this->appId);
        if (!$checkTokenRes) {
            exit($this->jsonResponse([], 0, '用户凭证异常', 10005));
        }
    }

    /**
     * 校验token
     * @param string $token
     * @param int $appId
     * @param int $uid
     * @return bool
     */
    protected function checkToken($token, $appId, $uid)
    {
        $result = $this->container->get('to8to.api.token')->checkToken($token, $appId, $uid);
        $msg = "token验证:token:{$token},appId:{$appId},uid:{$uid},验证结果:{$result}";
        $this->container->get('logger')->info($msg);
        if (empty($result)) {
            ExceptionHelp::throwApiProblemException(ErrorCode::USER_LOGIN_FAILED);
        }
        return true;
    }

    /**
     * 设置跨域访问权限
     */
    protected function enableAllowOrigin()
    {
        header("Access-Control-Allow-Origin: *");
    }

    /**
     * [简易]过滤字符串
     * 注意会将特殊字符转换为HTML实体
     * @param string $str 字符串内容
     * @return string
     */
    protected function filterStr($str)
    {
        if (empty($str)) {
            return '';
        }

        return addslashes(htmlspecialchars(strip_tags(trim($str))));
    }

    protected function isSuccessResult($result){
        if (empty($result)) {
            return $this->faildJsonResponse([],0,ErrorCode::getErrorMsg(ErrorCode::NO_RECORD),ErrorCode::NO_RECORD);
        }
        $success = $result['success'] ?? [];
        $faild = $result['faild'] ?? [];

        if ($faild || empty($success)) {
            return $this->faildJsonResponse($faild, 0);
        }
        if ($success) {
            return $this->successJsonResponse();
        }

        return $this->faildJsonResponse(
            [],
            0,
            ErrorCode::getErrorMsg(ErrorCode::UNKNOWN_ERROR),
            ErrorCode::UNKNOWN_ERROR
        );
    }

    /**
     * 校验ticket
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
     * 根据uid获取accountId
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
