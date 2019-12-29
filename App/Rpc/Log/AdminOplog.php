<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/27 下午1:40
 * class:AdminOplog.php
 * Project:YoSoos
 *
 */

namespace App\Rpc\Log;


use App\Rpc\Error\OnFail;
use App\Util\Common;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Rpc\Response;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Rpc\ServiceCall;

final class AdminOplog
{
    /**
     * @param int $deleteId
     * @param string $tableName
     * @param string $type insert|update|delete
     * AdminOplog 之 模型操作事件
     * @param int|null $rule_id
     */
    public static function adminGroup(int $deleteId,string $tableName,string $type, ? int $rule_id ) : void
    {
            $task = TaskManager::getInstance();
            $uid = Common::getUid();
            $task->async(function() use ($deleteId,$tableName,$rule_id,$type,$uid){
                $rpcClient = Rpc::getInstance()->client();
                $data = [
                    'admin_id'  => $uid,
                    'set_data_table'    => $tableName,
                    'action'    => $type
                ];
               if( $type == 'delete' )
               {
                   $data['rule_id'] = $rule_id;
               }
                $rpcClient->addCall('logger','AdminOplog@insertOplog',$data)
                    ->setOnFail(function( Response $rep, ServiceCall $severCall){
                        OnFail::fail($rep,$severCall);
                    });
                $rpcClient->exec(5.0);
            });
    }





}