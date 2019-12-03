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
    }



    /**
     * 格式化路由
     */
    private function format_url ( ? string $url = null) :string
    {
        if( is_null($url) ) 
            return '/';
        else
            return self::URL_SUFFIX.$url.self::URL_HTML_SUFFIX;

    }
}