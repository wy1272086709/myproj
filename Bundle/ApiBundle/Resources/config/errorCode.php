<?php
/**
 * Created by PhpStorm.
 * User: JACKY.YAO
 * Date: 2017/12/8
 * Time: 15:18
 * Update: lotus.kong 2018/08/01 23:20
 */

$errorCode = [

    //通用错误
    10008 => '您的登录状态已经失效啦，请重新登录!!!',
    10010 => '操作失败，请稍后重试',
    10011=>'签名校验失败',
    10012=>'请求过于频繁请稍后重试',
    10013=>'请求参数无效'
];

$container->setParameter('errorCode', $errorCode);