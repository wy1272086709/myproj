<?php
namespace CommonBundle\Service\Common;


use Psr\Log\LoggerInterface;
//use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use to8to\rpc\RPCClient;

class RpcClientService
{
    protected $rootDir;
    protected $monitorDir;

    protected $container = null;
    protected $rpcClient = null;
    protected $monitorService = null;
    protected $logger = null;

    public function __construct(
        ContainerInterface $container,
        RPCClient $rpcClient,
        MonitorService $monitorService
    ) {
        $this->container = $container;
        $this->rpcClient = $rpcClient;
        $this->monitorService = $monitorService;
        $this->logger = $this->container->get('logger');
        $this->rootDir = $this->container->get('kernel')->getRootDir() . '/../src/To8to/CommonBundle';
        $this->monitorDir = $this->container->get('kernel')->getRootDir() . '/../web/syslog/metrics/';
        $this->logDir = $this->container->get('kernel')->getRootDir() . '/../web';
    }

    /**
     * @param $rpcServiceName
     * @param $rpcMethodName
     * @param string $moduleName
     */
    public function init($rpcServiceName, $rpcMethodName, $moduleName = 'XgtRPC')
    {
        $this->rpcClient->init(
            $moduleName,
            $rpcServiceName,
            $this->rootDir,
            $this->monitorDir,
            $this->logDir
        );
        $this->rpcClient->method($rpcMethodName);
    }

    /**
     * @param $params
     * @return \to8to\rpc\RPCResponse
     */
    public function send($params)
    {
        $res =  $this->rpcClient->send($params);

        $this->monitor($res,true);

        return $res;
    }

    private function monitor($sendResObj = null, $needLog = false)
    {
        $resArrs = $sendResObj->getFullResponse();

        //设定你的监控指标
        $runTime = $this->rpcClient->getTotalTime();
        $rpcName = $this->rpcClient->getTarget();

        $labels = [
            'from' => 'CommonBundle\Service\Common\RpcClientService',
            'domain' => 'xgt.to8to.com',
            'success' => 'true',
            'name' => $rpcName,
        ];
        //设定指标具体数值
        $fields = [
            'h_duration' => $runTime,
            's_subTranscation' => 0,
            'g_fail' => 0,
            'g_count' => 1,
        ];
        //设定忽略选项
        $samples = [
            'sample' => '',
        ];
        $resArrs['status'] = isset($resArrs['status']) ? $resArrs['status'] : 0;
        // RPC失败则同时记录监控和日志
        if ($resArrs['status'] != 200) {
            $labels['success'] = 'false';
            $fields['g_fail'] = 1;
            if ($needLog) {
                $this->logger->error('RPC_ERROR', $resArrs);
            }
        }

        $this->monitorService->setCounter('CAT', 'xgt-globalrpc-service', $labels, $fields, $samples)->saveMonitor();
    }

}
