<?php
namespace App\Util;
use \EasySwoole\RedisPool\Redis;
class Common 
{
        public static function redis( ) 
        {
            return Redis::defer('redis');
        }
}
    