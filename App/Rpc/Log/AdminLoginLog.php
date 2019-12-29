<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/24 上午10:30
 * class:AdminLoginLog.php
 * Project:YoSoos
 *
 */

namespace App\Rpc\Log;
use App\Rpc\Error\OnFail;
use App\Util\Common;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Http\Request;
use EasySwoole\Rpc\Response;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Rpc\ServiceCall;

class AdminLoginLog
{


    public static function WriteLog( Request $req,int $uid ) : void
    {
        $task = TaskManager::getInstance();
        $ip = $req->getSwooleRequest()->header['x-real-ip'];
        $bower = $req->getSwooleRequest()->header['user-agent'];
        $task->async(function() use ($ip,$uid,$bower) {
            $redis = Common::redis();
            try {
                $key = 'task_manager_wirte_login_log';
                $status = $redis->setNx($key, '1');
                if ($status === 1) {
                    $redis->expire($key, 3);
                    $ip2region = \App\Util\Ip2Region::getInstance();
                    $ipArr = $ip2region->btreeSearch($ip);
                    $data = [
                        'uid' => $uid,
                        'time' => time(),
                        'bower' => $bower,
                        'ip' => $ip,
                    ];
                    $client = Rpc::getInstance()->client();
                    $client->addCall('logger', 'login@admlog', array_merge($data, $ipArr))
                        ->setOnFail(function (Response $rep,ServiceCall $serverCall)  {
                            /**
                             * 通信失败才走这里
                             */
                           OnFail::fail($rep,$serverCall);
                        });
                    $client->exec();
                }
            } catch (\Throwable $e) {
                throw $e;
            } finally {
                $redis->del($key);
            }
        });
    }

}