<?php

namespace CommonBundle\Service;

use Doctrine\ORM\EntityManager;
//use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;


class BaseService
{

    protected $entityManager = null;
    protected $repository = null;
    protected $container = null;
    protected $redis = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->redis = $this->container->get('snc_redis.default');
    }

    /**
     * getEntityManager
     * @description:
     * @return EntityManager $obj
     * @author     watson.zeng
     * @time       2018-03-29 13:46
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    protected function getRepository()
    {
        return $this->repository;
    }

    /**
     * 格式化日期格式
     * @param int $timeStamp 时间戳
     * @param string $dateFormat 日期格式,默认年-月-日 时:分:秒
     * @return false|string
     */
    protected function formatDate(int $timeStamp, string $dateFormat = 'Y-m-d H:i:s')
    {
        return $timeStamp > 0 ? date($dateFormat, $timeStamp) : '';
    }

    /**
     * 将请求参数拼接成缓存key
     * 这里的参数须是一维数组
     * @param array|string $args 请求参数
     * @return string
     */
    protected function generateArgsCacheKey($args)
    {
        if (!is_array($args)) {
            return $args;
        }

        $cacheKey = '';
        foreach ($args as $k => $v) {
            if (empty($cacheKey)) {
                $cacheKey = $v;
            } else {
                $cacheKey .= '_'.$v;
            }
        }

        return $cacheKey;
    }

    public function info($msg, $context = [])
    {
        $this->container->get('logger')->info($msg, $context);
    }

    public function error($msg, $context = [])
    {
        $this->container->get('logger')->error($msg, $context);

    }
}