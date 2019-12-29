<?php
namespace App\HttpController;
use App\Rpc\Error\OnFail;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Rpc\Response;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Rpc\ServiceCall;

class BaseController extends Controller
{

        function index()
        {
            /**
             *
             */
            var_dump('index');
        }

    protected function onException(\Throwable $e): void
        {
            /**
             * 所有控制器抛出异常后，在这里处理
             */
             go(function() use ($e){
                   go(function() use ($e){
                       $ip = \array_values(\swoole_get_local_ip());
                       $client = Rpc::getInstance()->client();
                       $client->addCall('logger','system@error',
                           [
                               'body'=> $e->getMessage(). ' at ' . $e->getLine() . ' in '.$e->getFile(),
                               'serv_ip'=> array_shift($ip),
                               'serv_name'=> gethostname(),
                               'type' => get_class($e),
                               'time'   => time(),
                           ])
                       ->setOnFail(function (Response $response,ServiceCall $serviceCall){
                           OnFail::fail($response,$serviceCall);
                       });
                       $client->exec(5);
                   });
                   go(function() use ($e) {

                       //这里警告发送短信或邮件
                   });
             });
              $this->err($e->getMessage() . ' at ' .$e->getFile() . ' in ' . $e->getLine());

        }


}