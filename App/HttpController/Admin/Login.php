<?php
namespace App\HttpController\Admin;
use App\Logic\Login as LogicLogin;
use App\Rpc\Log\AdminLoginLog;

class Login extends Base
{

    function index()
    {
     /*   $mode = Admin::create()->where('group_id',1);
        $has = $mode->count();
        var_dump($mode->lastQuery()->getLastQuery());
       var_dump($has);*/
    }

    /**
     * @return mixed
     * 具体的登录业务
     * @throws \Throwable
     */
    public function login()
    {
        $params = $this->request()->getParsedBody('data');
        if( !$params  ) return $this->argError();
        $validate = LogicLogin::vali($params);
        try{
            if ($validate === true) {
                $admin = LogicLogin::findByPhone($params['phone']);
                if ($admin) {
                    if (password_verify($params['password'], $admin['password'])) {
                        unset($admin['password']);
                        $msg = LogicLogin::isReadOnly($admin['group_id']);
                        if( is_string($msg) )
                        {
                            return $this->err($msg);
                        }
                        $success = LogicLogin::loginedSuccess($admin);
                        AdminLoginLog::WriteLog($this->request(),$admin['auto_id']);
                        return $this->success($success);
                    }
                    return $this->err('手机号或密码错误！');
                } else {
                    return $this->err('手机号或密码错误！');
                }
            } else {
                return $this->argError($validate);
            }
        }
        catch ( \Throwable $e)
        {
           throw $e;
        }

    }


    public function test()
    {
       /* $client = Rpc::getInstance()->client();
        $client->addCall('logger', 'System@smslog',['templet'=> 'test','suppliers'=>'fdsd','create_time'=>time()] )
            ->setOnFail(function (Response $rep,ServiceCall $serverCall)  {
               // 通信失败才走这里
                $error_arr = array_merge($rep->toArray(),$serverCall->getServiceNode()->toArray());
                OnFail::fail($error_arr);

            })
            ->setOnSuccess(function ( Response $response, ServiceCall $serviceCall){
                var_dump($response->toArray());
                var_dump($serviceCall->getServiceNode()->toArray());
                //  var_dump($serviceCall->getServiceNode()->getServiceName());
            });
        $client->exec(0.2);*/
        // $data=     \App\Logic\System::findAll(['is_read_only','is_read_only_msg']);

        /*$token = $this->createToken('abscs');
        $decode = \EasySwoole\Jwt\Jwt::getInstance()->decode($token);
        var_dump($decode->getIss());*/
        /* for($i=0;$i<250;$i++) {
             go(function () {
                 $ip = \array_values(\swoole_get_local_ip());
                 $client = Rpc::getInstance()->client();
                $client->addCall('logger', 'system@error',
                    [
                        'body' => '测试error日志',
                        'serv_ip' => array_shift($ip),
                        'serv_name' => gethostname(),
                        'type' => get_class($this),
                        'time' => time(),
                    ])
                    ->setOnFail(function (Response $e) {
                        var_dump($e);
                    });

                 $client->addCall('logger', 'system@smslog',
                     [
                         'phone' => '1847766332',
                         'status' => 'y',
                         'uid' => rand(1, 10),
                         'templet' => 'ceshi',
                         'create_time' => time(),
                     ])
                     ->setOnFail(function (Response $e) {
                         var_dump($e);
                     });
                 $client->exec(1.0);
             });
         }*/

        // $mode = Art::create()->connection('write');
        //  var_dump($mode->destroy(2) );
        //var_dump( Common::redis()->hGetAll(Constans::REDIS_ADMIN_ROLE . md5('1')));
        //     var_dump('test');
        $this->success();
    }
}