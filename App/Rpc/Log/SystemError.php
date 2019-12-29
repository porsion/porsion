<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/28 下午12:41
 * class:SystemError.php
 * Project:YoSoos
 *
 */

namespace App\Rpc\Log;


use App\Rpc\Error\OnFail;
use App\Util\Common;
use EasySwoole\Http\Request;
use EasySwoole\Rpc\Response;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Rpc\ServiceCall;

final class SystemError
{
    public static function exceptionLog( Request $req)
    {
        $limit = Common::limit($req);
        $client = Rpc::getInstance()->client();
        $data = [];
        $client->addCall('logger','listLog@systemError',$limit)
                ->setOnFail(function (Response $response, ServiceCall $serviceCall) use (&$data){
                    OnFail::fail($response,$serviceCall);
                    $data['status'] = false;
                })
                ->setOnSuccess(function(Response $response, ServiceCall $serviceCall) use (&$data){
                    $data['status'] = true;
                    $data['data'] = $response->toArray()['result'];
                });
        $client->exec();
        if( $data['status'] )
        {
            return $data['data'];
        }
        return [];
    }

    public static function delExceptionLog(array $id )
    {
        $client = Rpc::getInstance()->client();
        $data = [];
        $client->addCall('logger','delLog@systemError',$id)
            ->setOnFail(function (Response $response, ServiceCall $serviceCall) use (&$data){
                OnFail::fail($response,$serviceCall);
                $data['status'] = false;
            })
            ->setOnSuccess(function(Response $response) use (&$data){
                $data['status'] = true;
            });
        $client->exec();
        return $data;
    }
}