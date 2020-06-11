<?php
namespace CommonBundle\Exception;

/**
 * 此类中的异常信息，是允许直接展示给用户
 * Created by PhpStorm.
 * User: reed.chen
 * Date: 2017/9/22
 * Time: 10:18
 */

class InfoException extends \RuntimeException
{
    /**
     * 异常名称
     * @return string
     */
    public function getName()
    {
        return 'InfoException';
    }
}
