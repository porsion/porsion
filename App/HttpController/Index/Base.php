<?php
namespace App\HttpController\Index;
use App\Exception\{ LoginException , TokenException};
use App\Util\HttpStatusCode,
     App\HttpController\BaseController;
    
class Base extends BaseController
{
        public function index()
        {

        }


            


       /**
        * 请求之前，验证一下吧
        *
        * @param string|null $action
        * @return boolean|null
        */
        protected function onRequest( ? string $action) :? bool
        {
            
          try{
              $this->verifyToken( $this->request(),'admin');
              $this->verifyLogin( $this->request(),'admin');
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