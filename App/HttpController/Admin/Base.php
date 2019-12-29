<?php
namespace App\HttpController\Admin;
use App\Exception\RoleException;
use App\HttpController\BaseController;
use App\Exception\TokenException;
use App\Logic\System;
use App\Util\Common;
use App\Util\Constans;
use EasySwoole\Jwt\Exception;
use EasySwoole\Jwt\Jwt;

class Base extends BaseController
{
    use \App\Util\OnRequest,  \App\Util\HttpStatus;

    /**
     * 请求之前，验证一下吧
     *
     * @param string|null $action
     *
     * @return boolean|null
     * @throws Exception
     * @throws \EasySwoole\Component\Context\Exception\ModifyError
     */
    protected function onRequest( ? string $action) :? bool
        {

            $no_login_action = ['login','index'];
            $is_auth_role = true;
          if( $action && !in_array($action,$no_login_action) )
          {
              try{
                  $this->verifyToken( $this->request());
                  if($is_auth_role)
                  {
                      $this->authRole($this->request(),$action);
                  }
                  return true;
              }
              catch (TokenException $e )
              {
                  $this->noLogin();
                  return false;
              }
              catch ( RoleException $e)
              {
                  $this->noAuth();
                  return false;
              }
              catch( \EasySwoole\Jwt\Exception $e)
              {
                  $this->noAuth();
                  return false;
              }
              catch ( \EasySwoole\Component\Context\Exception\ModifyError $e )
              {
                  $this->noLogin();
                  return false;
              }
              catch ( Exception $e )
              {
                  throw $e;
              }
              return true;
          }
          return true;
        }

        protected function afterAction(?string $actionName): void
        {
            if( $actionName != 'login' )
            {
                $jwt = Jwt::getInstance()->setSecretKey(Constans::TOKEN_KEY);
                $result = $jwt->decode($this->request()->getHeaderLine('x-token'));
                $exp = $result->getExp();
                if(  $exp - time() <= ( 20*60) )
                {
                    $token_life_time = (int) System::findAll('token_life_time')['v'];
                    $new_token = Common::createToken($result->getIss(),$token_life_time * 60);
                    Common::redis()->expire(Constans::REDIS_ADMIN_USER_KEY . $result->getIss(), $token_life_time * 60);
                    $this->response()->withheader('new-token',$new_token);
                }
            }



        }
}