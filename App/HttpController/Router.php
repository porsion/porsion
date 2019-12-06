<?php
namespace App\HttpController;
use EasySwoole\Http\AbstractInterface\AbstractRouter,FastRoute\RouteCollector,
    EasySwoole\Http\Request,EasySwoole\Http\Response;

class Router extends AbstractRouter
{

    function initialize(RouteCollector $route)
    {
        $this->setGlobalMode(true);
        $route->addGroup(URL_SUFFIX ,function($r)
        {
            $r->addGroup('/index',function( $r ){
                $r->get(self::u('/index'),'Index/Index/index');
            });
            $r->addGroup( '/admin', function ($r) {
                $r->get(self::u('/index'),'Admin/Index/index');
            });
               
        });


        /**
         * 设置Method不匹配时的异常，需要返回false
         */
        $this->setMethodNotAllowCallBack(
            function (Request $request,Response $response){
                $response->withStatus(404)->write('the Method is dany');
                return false;//结束此次响应
            }
        );

        /**
         * 设置路由找不到时的异常 需要返回 false
         */
        $this->setRouterNotFoundCallBack(
            function (Request $request,Response $response){
                $response->withStatus(404)->write('the action not found');
                return false;//结束此次响应
            }
        );
    }


























    /**
     * 格式化一个URL
     *
     * @param string $url
     * @return string
     */
    private static function u( ?string  $url = null ) : string
    {
        if( is_null($url) || empty($url)) 
            return '/';
        else
            return $url . URL_HTML_SUFFIX;
    }

}