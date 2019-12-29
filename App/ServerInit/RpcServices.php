<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/19 下午6:40
 * class:RpcServices.php
 * Project:YoSoos
 *
 */

namespace App\ServerInit;

use EasySwoole\Rpc\Rpc;
use EasySwoole\Rpc\Config as RpcConfig;
class RpcServices extends AbstractInit
{
    /**
     * 注册Rpc服务
     * 如 192.168.137.11主机上有某些服务，需有注册，所以控制器里使用的时候就可以直接使用了
     */
    public static function init(): void
    {
        /**
         * 11=>user服务
         * 10=>同步中心(队列)
         * 4 =>日志中心
         */
        $rpcHost = ['11','10','4'];
        foreach( $rpcHost as $v )
        {
            $config = new RpcConfig();
            $config->setServerIp('192.168.137.'.$v);
            $config->setNodeManager( Redis::getRedisNodeManager());
            Rpc::getInstance($config);
        }

    }
}