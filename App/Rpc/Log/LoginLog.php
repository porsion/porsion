<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/30 下午2:39
 * class:LoginLog.php
 * Project:YoSoos
 *
 */

namespace App\Rpc\Log;


use App\Model\Admin;
use App\Rpc\Error\OnFail;
use App\Util\Common;
use EasySwoole\Http\Request;
use EasySwoole\ORM\Exception\Exception;
use EasySwoole\Rpc\Response;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Rpc\ServiceCall;

final class LoginLog
{
    /**
     * @param Request $req
     *
     * @return array
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws Exception
     * @throws \Throwable
     * 后台用户登录日志列表
     */
      public static function AdminLoginLog( Request $req)
      {
          $limit = Common::limit($req);
          $arg = ['limit'=>$limit];
          $client = Rpc::getInstance()->client();
          $data = [];
          $client->addCall('logger','listLog@adminLoginLog',$arg)
              ->setOnFail(function(Response $response,ServiceCall $serviceCall){
                  OnFail::fail($response,$serviceCall);
              })
              ->setOnSuccess(function(Response $response) use (&$data) {
                  $tempData = $response->toArray();
                  if( $tempData['msg'] == 'success' )
                  {
                      $data = $tempData['result'];
                  }
              });
          $client->exec(5);
          if( $data[0] )
          {
                $uids = array_unique( array_column($data[0],'uid'));
                $user_data = Admin::create()->where($uids)->field('auto_id,name')->indexBy('auto_id');
                foreach($data[0] as $k => $v)
                {
                    $data[0][$k]['user_name'] = $user_data[$v['uid']]['name'];
                    $data[0][$k]['time'] = date('Y-m-d H:i:s',$v['time']);
                }
          }
          return $data;
      }

    /**
     * @param Request $req
     *
     * @return int
     * 批量或单个删除后台用户登录日志
     */
      public static function adminLoginLogDel( Request $req)
      {
          $ids = $req->getParsedBody('ids');
          if( !$ids ) return 0;
          $client = Rpc::getInstance()->client();
          $ret = 0;
          $client->addCall('logger','delLog@adminLoginLogDel',['ids'=>$ids])
              ->setOnFail(function(Response $response,ServiceCall $serviceCall){
                  OnFail::fail($response,$serviceCall);
              })
              ->setOnSuccess(function(Response $response) use (&$ret){
                  $temData = $response->toArray();
                  if( $temData['msg']  == 'success' || $temData['result'] == 'ok')
                  {
                      $ret = $temData['result'];
                  }
              });
          $client->exec(5);
          return $ret;

      }
}