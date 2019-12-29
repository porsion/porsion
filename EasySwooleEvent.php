<?php
namespace EasySwoole\EasySwoole;
use EasySwoole\EasySwoole\
    {
        Swoole\EventRegister,
        AbstractInterface\Event,
    };
use EasySwoole\Http\
    {
        Request,Response
    };
use App\ServerInit\{
        Mysql,
        Redis,
        RpcServices,
    };
class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
            /**
             * 初始化Mysql
            */
             Mysql::init();
            /**
             * 初始化 Redis
             */

            Redis::init();

            /**
             * 初始化生产者
             *
             */
           //  Producer::init();

            /**
             * 注册各个rpc服务
             * 必须在redis初始化之后
             */
            RpcServices::init();
    }

    public static function mainServerCreate(EventRegister $register)
    {

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        $request->ip = $request->getHeader('x-real-ip');
        // TODO: Implement onRequest() method.
         return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}