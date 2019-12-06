<?php
namespace App\HttpController\Admin;
use App\HttpController\BaseController,
    App\Util\HttpStatusCode;
use App\Exception\{ LoginException , TokenException};
class Base extends BaseController
{

        public function index(){}


       /**
        * 请求之前，验证一下吧
        *
        * @param string|null $action
        * @return boolean|null
        */
        protected function onRequest( ? string $action) :? bool
        {
          // 添加跨域设置，建议去nginx里去设置
          // $this->response()->withHeader('Access-Control-Allow-Origin', '*');
          // $this->response()->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
          // $this->response()->withHeader('Access-Control-Allow-Credentials', 'true');
          // $this->response()->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
          // if ($this->request()->getMethod() === 'OPTIONS') {
          //   $this->response()->withStatus(HttpStatusCode::SUCCESS);
          //     return false;
          // }
            
          try{
              $this->verifyToken( $this->request(),'index');
              $this->verifyLogin( $this->request(),'index');
          }
          catch( LoginException $e) {
            $this->noLogin();
            return false;
          }
          catch (TokenException $e )
          {
            $this->noLogin();
            return false;
          }
          return true;
            //$token = $this->request->getHeaderLine('X-token');
           // $obj = \EasySwoole\Jwt\Jwt::getInstance()->algMethod('AES')->setSecretKey('eww')->setIss()->publish(); // 签发

           //$jwt =  \EasySwoole\Jwt\Jwt::getInstance();

          // $result = $jwt -> decode($token);//验证
    
            // var_dump($result);
            
            // switch ($result->getStatus())
            // {
            //     case  1:
            //         echo '验证通过';
            //         break;
            //     case  2:
            //         echo '验证失败';
            //         break;
            //     case  3:
            //         echo 'token过期';
            //         break;
            // }

        }


   


        
        /**
         * 请求通过了干点啥
         *
         * @param string|null $actionName
         * @return void
         */
        protected function afterAction(?string $actionName): void
        {

        }
}