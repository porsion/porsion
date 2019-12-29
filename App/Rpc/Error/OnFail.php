<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/24 下午12:33
 * class:OnFail.php
 * Project:YoSoos
 *
 */

namespace App\Rpc\Error;
use App\Model\RpcErrorLog;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Rpc\Response;
use EasySwoole\Rpc\ServiceCall;

class OnFail
{
    public static function fail(Response $rep,ServiceCall $serviceCall  ) : void
    {
        $task = TaskManager::getInstance();
        $arr = array_merge($rep->toArray(),$serviceCall->getServiceNode()->toArray());
        $task->async(function() use ($arr){
            RpcErrorLog::create($arr)->save();
        });
    }
}