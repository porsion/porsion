<?php
namespace App\OnMainServerCreate;
use EasySwoole\Queue\{
    Driver\Redis as QueueRedis,
    Job
};
use EasySwoole\EasySwoole\Config;
use EasySwoole\RedisPool\RedisPool;
use EasySwoole\Redis\Config\RedisConfig;

class Producer extends AbstractInit 
{
    /**
     * 注册生产者
     *
     * @return void
     */
        public static function init() : void 
        {
            $config =  Config::getInstance()->getConf('REDIS') ;
            \App\Queue\Producer::getInstance( new QueueRedis( new RedisPool( new RedisConfig($config) ) ) );
        }
}