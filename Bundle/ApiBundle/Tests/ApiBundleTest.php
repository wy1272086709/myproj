<?php
// +--------------------------------------------------------------------------
// | ProjectName :t8t-tbt-api
// +--------------------------------------------------------------------------
// | Description :ApiBundleTest.php 
//+--------------------------------------------------------------------------
// | Author: xiaoyuqin 
// +--------------------------------------------------------------------------
// | Version:0.0.1                Date:2018/12/7 13:50
// +--------------------------------------------------------------------------


namespace ApiBundle\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiBundleTest extends WebTestCase
{
    protected static function createClient(array $options = array (), array $server = array ())
    {
        $options = self::initOptions($options);

        //return parent::createClient($options, $server);
        $kernel = static::bootKernel($options);

        $client = $kernel->getContainer()->get($options['environment']);
        $client->setServerParameters($server);

        return $client;
    }


    protected static function initOptions(array $options)
    {
        if (isset($options['environment']) || empty($options['environment'])) {
            $options['environment'] = 'api_dev';
        }

        return $options;

    }

}