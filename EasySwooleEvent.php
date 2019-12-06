<?php
namespace EasySwoole\EasySwoole;
use EasySwoole\EasySwoole\
    { 
        Swoole\EventRegister,
        AbstractInterface\Event,
        ServerManager
    };
use EasySwoole\Http\
    {
        Request,Response
    };
use App\OnMainServerCreate\{
        Mysql,
        Redis,
        Producer
    };

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        
    }

    public static function mainServerCreate(EventRegister $register)
    {
     try{
            /**
             * 注册Mysql
            */
            Mysql::init();
            /**
             * 注册 Redis
             */
            Redis::init();


            /**
             * 注册生产者
             *
             */
            Producer::init();

            /**
             * 注册消费者
             */

            ServerManager::getInstance()->addProcess( new \App\Queue\Consumer(),'Queue');
          //  $queue = new \App\Queue\Producer();
          
                // while(true)
                // {
                //     $job = new Job();
                //     $job->setJobData(['time'=>\time()]);
                //     \App\Queue\Producer::getInstance()->producer()->push($job);
                //     (new Logger())->log('producer is start');
                //     sleep(2);
                //    // \Swoole\Coroutine::sleep(3);
                // }
               
          
         }
         catch( \Throwable $e)
         {
             var_dump($e->getMessage());
         }    
       
       
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
         return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}