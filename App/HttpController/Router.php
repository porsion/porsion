<?php
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;
class Router extends AbstractRouter
{
    /**
     * @var string
     * 路由后缀
     */
    const URL_HTML_SUFFIX = '.htm';

    /**
     * @var string
     * 路由前缀
     */
    const URL_SUFFIX = '/api';
    public function initialize( RouteCollector $route )
    {
        /**
         * 所有的路由文件都写在这里
         */
        $route->get($this->format_url('/index'),'Index/index');
        $route->get($this->format_url('/login'),'Login/index');
        $route->post($this->format_url('/login'),'Login/index');
        $route->get($this->format_url('/logout'),'Login/logout');
        $route->get($this->format_url('/initmenu'),'Login/initmenu');
        $route->get($this->format_url('/initmsg'),'Login/initmsg');
        
    }



    /**
     * 格式化路由
     * @param $url 路由
     * @return string
     */
    private function format_url ( ? string $url = null) :string
    {
        if( is_null($url) ) 
            return '/';
        else
            return self::URL_SUFFIX.$url.self::URL_HTML_SUFFIX;
    }
}