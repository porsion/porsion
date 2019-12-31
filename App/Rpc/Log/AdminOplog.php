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


use App\Model\Admin;
use App\Model\Rule;
use App\Rpc\Error\OnFail;
use App\Util\Common;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Http\Request;
use EasySwoole\ORM\Exception\Exception;
use EasySwoole\Rpc\Response;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Rpc\ServiceCall;

final class AdminOplog
{
    /**
     * @param array $param
     * @param string $tableName
     * @param string $action update|insert|delete
     * @param int|null $rule_id
     */
    public static function insAdminOplog(array $param,string $tableName,string $action, ? int $rule_id ) : void
    {
            $task = TaskManager::getInstance();
            $uid = Common::getUid();
            $task->async(function() use ($param,$tableName,$rule_id,$action,$uid){
                $rpcClient = Rpc::getInstance()->client();
                $data = [
                    'admin_id'  => $uid,
                    'set_data_table'    => $tableName,
                    'action'    => $action,
                    'param'     => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
               if( $action == 'delete' && $rule_id > 0 )
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


    /**
     * @param Request $req
     * 列出后台用户的操作日志
     *
     * @return array
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws Exception
     * @throws \Throwable
     */
    public static function listAdminOplog( Request $req)
    {
        $rpcClient = Rpc::getInstance()->client();
        $data = [null,0];
        $arg = [
            'limit'  => [ 'limit'=> $req->getQueryParam('limit'), 'page' => $req->getQueryParam('page')]
        ];
        $rpcClient->addCall('logger','AdminOplog@listAdminOplog',$arg)
            ->setOnSuccess(function( Response $rep) use (&$data){
                $repArr = $rep->toArray();
                if( $repArr['msg'] == 'success')
                {
                    $data = $repArr['result'];
                }
            })
            ->setOnFail(function(Response $rep, ServiceCall $serviceCall){
                OnFail::fail($rep,$serviceCall);
            });
        $rpcClient->exec(5.0);
        if( isset($data[0]) )
        {
            $admin_ids = array_column($data[0],'admin_id');
            $rule_ids = array_column($data[0],'rule_id');
            $admin_users = Admin::create()->where(array_unique($admin_ids))
                        ->field('name,auto_id')->indexBy('auto_id');
            $rules = Rule::create()->where(array_unique($rule_ids))
                    ->field('title,auto_id')->indexBy('auto_id');
           foreach($data[0] as $k => $v)
           {
               if( $v['admin_id'] > 0 )
               $data[0][$k]['admin_user_name'] = $admin_users[$v['admin_id']]['name'];
               if( $v['rule_id'] > 0 )
               $data[0][$k]['rule_title']       = $rules[$v['rule_id']]['title'];
               $data[0][$k]['create_time']      = date('Y-m-d H:i:s',$v['create_time']);
           }
        }
        return $data;
    }


    /**
     * @param Request $req
     *
     * @return bool
     * 删除后台用户的操作日志
     */
    public static function adminDel( Request $req) : bool
    {
        $ret = false;
        $ids = $req->getParsedBody('ids');
        $client = Rpc::getInstance()->client();
        $client->addCall('logger','AdminOplog@delAdminOplog',['ids' => $ids])
            ->setOnSuccess(function(Response $response) use (&$ret){
                $repArr = $response->toArray();
                if( $repArr['msg'] == 'success')
                {
                    $ret = true;
                }
            })
        ->setOnFail(function(Response $response,ServiceCall $serviceCall){
            OnFail::fail($response,$serviceCall);
        });
        $client->exec(5);
        return $ret;
    }



}