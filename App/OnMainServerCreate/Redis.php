<?php
namespace App\OnMainServerCreate;
use EasySwoole\
    {
        RedisPool\Redis as PoolRedis,
        Redis\Config\RedisConfig,
        EasySwoole\Config,
        RedisPool\RedisPoolException
    };
class Redis extends AbstractInit 
{
    public static function init() :void 
    {

        $config = Config::getInstance()->getConf('REDIS') ;
        if( !$config ) throw new \Exception('Redis config is requried');
        $redis = PoolRedis::getInstance()->register('redis',new RedisConfig($config) );
        $redis->setMinObjectNum(5);
        $redis->setMaxObjectNum(20);

    }

}