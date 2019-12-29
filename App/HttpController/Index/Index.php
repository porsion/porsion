<?php
namespace App\HttpController\Index;

use EasySwoole\Rpc\Response;
use EasySwoole\Rpc\Rpc;

class Index extends Base
{
    function index()
    {
        $i = 0;
        return $this->success($i);
    }

    public function setPwd()
    {
        $post = $this->request()->getParsedBody();
        // 验证数据

        // 数据验证通过
        $res = true;
        // RPC过程调用
        $client = Rpc::getInstance()->client();
        $client->addCall('user','update@updateLockField',array_merge($post , ['__lock_field' => 'password']))
            ->setOnSuccess(function(Response $rep) use ($client){
              //  $client->addCall('logger','user@setPwd');
                var_dump('success',$rep);
            })
        ->setOnFail(function( Response $rep) use (&$res,$client){
            $res = false;
            var_dump('error',$rep);
            // 调用失败，记录调用失败日志日志
         /*   go(function() use ($rep,$client ){
                $rep = $rep->toArray();
                $client->addCall('logger','system@callRpcError',$rep);
                $client->exec(2.0);
            });*/

        });

        $client->exec(2.0);
        if($res)
        {
           /* go(function(){});
            go(function(){});*/
            return $this->success();
        }
        return $this->err();
    }




    function login()
    {
        var_dump($this->request()->getQueryParams());
        return $this->success();

        /*  try{
          $data = User::create()->findOne(356);
          return $this->success($data);
      }
      catch ( ORMException $e)
      {
          //throw new ORMException($e->getMessage());
          var_dump($e->getMessage());
      }
      catch ( PoolEmpty $e)
      {
          var_dump($e->getMessage(),$e->getFile(),$e->getLine());
      }*/

        /* $redis = Redis::defer('redis');
         $redis->set('abd','bbbbb');
         $ret = $redis->get('abd');*/


    }

    public function test()
    {
        $client = Rpc::getInstance()->client();
        /*   $client->addCall('user','login',['user_id' =>10,'class'=>'user'])
                ->setOnSuccess(function( Response $res ) use ( &$ret){
                    $ret = $res;
                } )
                ->setOnFail( function( Response $res) use (&$ret){
                    $ret = $res;
                });

          //  $client->addCall('sync','user@toMysql',['user_id'=>10]);
         //   $client->addCall('logger','tree',['user_id'=>10,'class'=>'tree']);
         //   $client->addCall('logger','system@test1',['user_id'=>10,'class'=>'test']);
         */

        /* $chan = new \Swoole\Coroutine\Channel(2);
          go(function() use ($chan){
              $chan->push(['abd' => 'adb']);
          });
          go(function() use ($chan){
              $chan->push(['aaa'=>'aaa']);
          });
          $result = [];
          for ($i = 0; $i < 2; $i++)
          {
              $result += $chan->pop();
          }*/



    }
   
}