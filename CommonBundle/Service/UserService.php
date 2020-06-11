<?php
/**
 *
 * @Author: sunrise.wang
 * @Date: 2019-03-06
 *
 */
namespace CommonBundle\Service;

use CommonBundle\Utils\CacheKeyPreConfig;
use CommonBundle\Utils\PicDomain;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Event\Event;
use to8to\config\LogicConfig;

class UserService
{

    //获取用户信息相关常量
    const MOBILE_API_URL = 'http://mobileapi.to8to.com/index.php';
    const USR_NAME = 'tbt-usr-info';
    const USR_TICKET = 'to8toUserInfoForAppApi';
    const USER_DEFAULT_AVATAR = 'https://img.to8to.com/newheadphoto/200/10_1x.jpg';

    protected static $curlInstance = null;

    public function __construct(
        ContainerInterface $container,
        To8toMembersService $to8toMembersService
    )
    {
        $this->container = $container;
        $this->to8toMembersService = $to8toMembersService;
        $this->logger = $this->container->get('logger');
        $this->rootDir = $this->container->get('kernel')->getRootDir();
    }

    /**
     * clear cookies while logout
     * @desc 删除带特定前缀的cookie 并且清除全局变量用户id
     */
    public function clearCookies()
    {
        $this->mSetCookie('auth', '', -3600);
        $this->mSetCookie('la', '', -3600);
        $this->mSetCookie('nick', '', -3600);
        $this->mSetCookie('uid', '', -3600);
        $this->mSetCookie('ind', '', -3600);
        $this->mSetCookie('styleid', '', -3600);
        $this->mSetCookie('username', '', -3600);
        $this->mSetCookie("tbdl_login", '', -3600);

        $this->mSetCookie('qqtoken', '', -3600);
        $this->mSetCookie('weibotoken', '', -3600);
        $this->mSetCookie('weixintoken', '', -3600);

        $this->mSetCookie('fcm_sid', '', -3600);
        $this->mSetCookie('fcm_admin', '', -3600);
        $this->mSetCookie('fcm_tid', '', -3600);
        $this->mSetCookie('fcm_auth', '', -3600);
        $this->mSetCookie('username_t', '', -3600);
        $this->mSetCookie('tbdl_login', '', -3600);
        $this->mSetCookie('loginway', '', -3600);
    }

    /**
     * set cookie
     * @desc 设置可带前缀的cookie
     * @param string $var
     * @param mix    $value
     * @param int    $life
     * @param bool   $prefix
     */
    public function mSetCookie($var, $value, $life = 0, $prefix = 1)
    {
        $response = new Response();
        $cookieconfig = $this->getCookieConfig();
        $response->headers->setCookie(
            new Cookie(
                ($prefix ? $cookieconfig['cookie']['pre'] : '') . $var,
                $value,
                $life ? time() + $life : 0,
                $cookieconfig['cookie']['path'],
                $cookieconfig['cookie']['domain'],
                $_SERVER['SERVER_PORT'] == 443 ? 1 : 0
            )
        );
        $response->sendHeaders();
    }

    /**
     * encode or decode string or set expire
     * @desc 字符串加密方式
     * @param        $string
     * @param string $operation
     * @param string $key
     * @param int    $expiry
     * @return string
     */
    public function authCode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        $to8to_cookie_config = $this->getCookieConfig();
        $to8to_auth_key = md5($to8to_cookie_config['authkey'] . "to8torobin");
        $key = md5($key ? $key : $to8to_auth_key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(
            md5(microtime()),
            -$ckey_length
        )) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf(
                                                                                              '%010d',
                                                                                              $expiry ? $expiry + time() : 0
                                                                                          ) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr(
                                                                                            $result,
                                                                                            10,
                                                                                            16
                                                                                        ) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

    /**
     * get cookie config
     * 获取cookie配置
     * @return mixed
     */
    public function getCookieConfig()
    {
        $rootDir = $this->container->get('kernel')->getRootDir() . '/../src/To8to/CommonBundle';
        $logDir = $this->container->get('kernel')->getProjectDir() . '/web';
        $cookieConfigObj = new LogicConfig('Cookie');
        $configItems = $cookieConfigObj->loadArray($rootDir, $logDir);

        return $configItems;
    }

    /**
     * get head photo by uid
     * @description 根据用户id 获取对应的头像
     * @author      crispan.chen 2018年3月23日
     * @param int $uid 用户id
     * @return string
     */
    public function getHeadPhoto($uid)
    {
        $num = $uid % 905 + 1;
        $file = "https://img.to8to.com/headphoto/{$num}.jpg";

        return $file;
    }

    /**
     * 创建CURL实例
     * @return null|object
     */
    protected function getCurlIns()
    {
        if (self::$curlInstance == null) {
            self::$curlInstance = $this->container->get('to8to.common.curl');
            self::$curlInstance->setOption(CURLOPT_CONNECTTIMEOUT_MS, 2000);
            self::$curlInstance->setOption(CURLOPT_TIMEOUT_MS, 8000);
            self::$curlInstance->setOption(CURLOPT_HEADER, 0);
            self::$curlInstance->setOption(CURLOPT_VERBOSE, false);
        }

        return self::$curlInstance;
    }

    /**
     * batch get user info
     * 批量获取用户信息
     * 目前仅返回用户id、昵称、头像、身份标识
     * @param array  $uidArr 用户id索引数组
     * @param string $names  用户名字符串
     * @return array
     */
    public function batchGetUserInfo($uidArr, $names = '')
    {
        if (empty($uidArr) && empty($names)) {
            return [];
        }

        //无止境的入参兼容
        $uidArr = !empty($uidArr) ? (is_array($uidArr) ? $uidArr : [$uidArr]) : [];
        $uidArr = !empty($uidArr) ? array_values(explode(',', implode(',', $uidArr))) : [];
        $nameStr = !empty($names) ? (is_array($names) ? implode(',', $names) : $names) : '';
        $accUserService = $this->container->get(AccUserService::class);
        if (!empty($nameStr)) {
            $userArr = $this->to8toMembersService->getUserByName(explode(',', $nameStr), $accUserService->getLocalFields());
            $uidArr = array_merge($uidArr, array_column($userArr, 'uid'));
        }

        $res = [];
        $wwwRedis = $this->container->get('snc_redis.www');
        $keyArr = $this->makeUserCacheKey($uidArr);
        $keyArr && $res = $wwwRedis->mget($keyArr);
        $cachedUidArr = [];
        if (!empty($res)) {
            foreach ($res as $k => &$v) {
                if (!empty($v)) {
                    $v = json_decode($v, true);
                    $cachedUidArr[] = (int)$v['uid'];
                } else {
                    unset($res[$k]);
                }
                unset($v);
            }
        }
        $needCacheUidArr = array_diff($uidArr, $cachedUidArr);
        if (!empty($needCacheUidArr)) {
            $this->logger->info('需要查询的用户.', ['uid' => implode(',', $needCacheUidArr)]);
            $needCachedUserInfo = $accUserService->getAccUser($needCacheUidArr, 0);
            if ($needCachedUserInfo) {
                $setData = [];
                foreach ($needCacheUidArr as $v) {
                    !empty($needCachedUserInfo[$v]) && $setData[CacheKeyPreConfig::APP_USER . $v] = json_encode($needCachedUserInfo[$v]);
                }
                !empty($setData) && $wwwRedis->mset($setData);
                $res = array_merge($res, array_values($needCachedUserInfo));
            }
        }
        $this->logger->info('获取用户信息.', ['uid' => implode(',', $uidArr), 'name' => $nameStr, 'res' => $res]);

        //基本信息
        $res = array_column($this->fixUserInfo($res), null, 'uid');

        $userInfo = [];
        if (!empty($res)) {
            //获取头像
            $avatarRes = $this->getUserAvatar(array_keys($res), $wwwRedis);

            //获取用户第三方认证标识
            if (empty($uidArr)) {
                //提取用户id
                $uidArr = array_column($res, 'uid');
            }
            $uidStr = implode(',', $uidArr);
            $cmsUserIdentityService = $this->container->get(CmsUserIdentityService::class);
            $certRes = $cmsUserIdentityService->getUserIdentityListByUids($uidArr);
            $this->logger->info('获取用户第三方认证信息.', ['uid' => $uidStr, 'res' => $certRes]);
            //以uid为key
            foreach ($res as $k => $v) {
                $userInfo[$v['uid']] = array_merge($v, [
                    'authorAvatar'         => !empty($avatarRes[$v['uid']]['avatar']) ? $avatarRes[$v['uid']]['avatar'] : '',
                    'identificationDesc'   => $certRes[$v['uid']]['identification_desc'] ?? '',
                    'identificationStatus' => $certRes[$v['uid']]['identity_status'] ?? 0,
                    'identificationType'   => $certRes[$v['uid']]['identity_type'] ?? 0,
                    'identificationTime'   => $certRes[$v['uid']]['create_time'] ?? 0,
                    'identificationPic'    => $certRes[$v['uid']]['identity_pic'] ?? '',
                ]);
            }
        } else {
            $this->logger->error('获取不到用户信息.', ['uid' => implode(',', $uidArr), 'name' => $nameStr]);
        }

        return $userInfo;
    }

    //声明用户查询事件的监听
    public function queryUserByEvent($event)
    {
        $uids = $event->getUidList();
        if (boolval($uids)) {
            $userList = $this->batchGetUserInfo($uids);
            $event->setUserList($userList);
        }
        $accountIds = $event->getAccountIds();
        if (boolval($accountIds)) {
            $userContentService = $this->container->get(UserContentService::class);
            $event->setCenterUserList($userContentService->batchFindByIds($accountIds));
        }
    }

    /**
     * get user by username
     * 根据用户名查询用户
     * @param $username
     * @return array|mixed
     */
    public function getUserByUsername($username)
    {
        $user = ['uid' => 0, 'username' => '', 'nickname' => '', 'headimgurl' => ''];
        $userList = $this->batchGetUserInfo([], [$username]);
        foreach ($userList as $val) {
            if ($val['username'] == $username) {
                $user = $val;
            }
        }

        return $user;
    }

    protected function fixUserInfo($userInfo)
    {
        if (empty($userInfo) || !is_array($userInfo)) {
            return [];
        }

        $fixUserInfo = [];
        foreach ($userInfo as $k => $v) {
            if (!$v || !isset($v['uid']))
            {
                continue;
            }
            
            $fixUserInfo[] = [
                'uid'            => (int)$v['uid'],
                'username'       => isset($v['username'])?$v['username']:'',
                'authorId'       => (int)$v['uid'],
                'authorName'     => isset($v['nick'])?$v['nick']:'',
                'authorIdentity' => isset($v['indentity'])?(int)$v['indentity']:0,
                'accountId'      => isset($v['account_id'])?(int)$v['account_id']:0,
                'deleted'        => isset($v['deleted'])?(int)$v['deleted']:0
            ];
        }

        return $fixUserInfo;
    }

    protected function makeUserCacheKey($uidArr)
    {
        if (empty($uidArr) || !is_array($uidArr)) {
            return [];
        }

        foreach ($uidArr as &$v) {
            $v = CacheKeyPreConfig::APP_USER . $v;
            unset($v);
        }

        return $uidArr;
    }

    protected function makeUserAvatarKey($uidArr)
    {
        if (empty($uidArr) || !is_array($uidArr)) {
            return [];
        }

        foreach ($uidArr as &$v) {
            $v = CacheKeyPreConfig::APP_USER_AVATAR . $v;
            unset($v);
        }

        return $uidArr;
    }

    /**
     * get user avatar by uid array and redis
     * @param $uidArr
     * @param null $wwwRedis
     * @return array
     */
    public function getUserAvatar($uidArr, $wwwRedis = null)
    {
        if (empty($uidArr) || !is_array($uidArr)) {
            return [];
        }

        if (empty($wwwRedis)) {
            $wwwRedis = $this->container->get('snc_redis.www');
        }

        $keyArr = $this->makeUserAvatarKey($uidArr);
        $keyArr && $res = $wwwRedis->mget($keyArr);
        $cachedUidArr = [];
        if (!empty($res)) {
            foreach ($res as $k => &$v) {
                if (!empty($v)) {
                    $v = json_decode($v, true);
                    $cachedUidArr[] = (int)$v['uid'];
                } else {
                    unset($res[$k]);
                }
                unset($v);
            }
        }
        $needCacheUidArr = array_diff($uidArr, $cachedUidArr);
        if (!empty($needCacheUidArr)) {
            $this->logger->info('需要查询头像的用户.', ['uid' => implode(',', $needCacheUidArr)]);
            $needCachedUserInfo = $this->getPortrait($needCacheUidArr);
            if ($needCachedUserInfo) {
                $setData = [];
                foreach ($needCacheUidArr as $v) {
                    !empty($needCachedUserInfo[$v]) && $setData[CacheKeyPreConfig::APP_USER_AVATAR . $v] = json_encode($needCachedUserInfo[$v]);
                }
                !empty($setData) && $wwwRedis->mset($setData);
                $res = array_merge($res, array_values($needCachedUserInfo));
            }
        }

        return $res && is_array($res) ? array_column($res, null, 'uid') : [];
    }

    /**
     * get user headphoto by uid array
     * 获取用户头像路径
     * 这里在本地环境可能会一直拿不到用户上传的头像,因为本地to8to代码目录下还有branch,trunk等分支
     * @param array $uidArr
     * @return array
     */
    public function getPortrait($uidArr)
    {
        if (empty($uidArr) || !is_array($uidArr)) {
            return [];
        }

        foreach ($uidArr as &$v) {
            $v = (int)$v;
            $filePath = 'user/' . ($v % 100) . "/headphoto_$v.jpg";
            if (!is_file($this->rootDir . '/../../to8to/pic/' . $filePath)) {
                $num = $v % 20 + 1;
                $filePath = PicDomain::IMG_DOMAIN_HTTPS . 'newheadphoto/100/' . $num . '_0.5x.jpg';
            } else {
                $filePath = PicDomain::PIC_DOMAIN_HTTPS . $filePath . '?' . time();
            }
            $filePathArr[$v] = [
                'uid'    => $v,
                'avatar' => $filePath
            ];

            unset($v);
        }

        return $filePathArr;
    }
}
