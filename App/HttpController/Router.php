<?php
namespace App\HttpController;
use EasySwoole\Http\AbstractInterface\AbstractRouter,FastRoute\RouteCollector,
    EasySwoole\Http\Request,EasySwoole\Http\Response;

/**
 * Class Router
 * @package App\HttpController
 */

class Router extends AbstractRouter
{
    function initialize(RouteCollector $route)
    {
        $this->setGlobalMode(true);
        $route->addGroup(URL_SUFFIX ,function($r)
        {
            /**
             * 前台所有路由
             */
            $r->addGroup('/index',function( $r ){
                $r->get(self::u('/index'),'Index/Index/index');
                $r->get(self::u('/login'),'Index/Index/login');
                $r->addGroup('/user',function($r){
                    $r->post(self::u('/pwd'),'Index/Index/setPwd');
                });

            });




            /**
             * 后台所有路由
             */
            $r->addGroup( '/admin', function ($r) {
                $r->get(self::u('/index'),'Admin/Login/index');
                $r->post(self::u('/login'),'Admin/Login/login');
                $r->get(self::u('/set'),'Admin/System/set');
                $r->get(self::u('/rule'),'Admin/Rule/index');
                $r->addGroup('/rule',function($r){
                    $r->get(self::u('/add'),'Admin/Rule/add');
                    $r->post(self::u('/save'),'Admin/Rule/save');
                    $r->get(self::u('/del'),'Admin/Rule/del');
                  //  $r->get(self::u('/find'),'Admin/Rule/find');
                });
                /**
                 * 配置项
                 */
                $r->addGroup('/system',function($r){
                    $r->post(self::u('/set_switch'),'Admin/System/setSwitch');
                    $r->get(self::u('/list'),'Admin/System/list');
                    $r->post(self::u('/save'),'Admin/System/save');
                    $r->get(self::u('/clear'),'Admin/System/clear');
                });
                /**
                 * 后台的用户组左侧菜单
                 */
                $r->addGroup('/role_menu',function($r){
                    $r->get(self::u('/index'),'Admin/RoleMenu/index');
                    $r->get(self::u('/edit'),'Admin/RoleMenu/edit');
                    $r->post(self::u('/save'),'Admin/RoleMenu/save');
                    $r->get(self::u('/add'),'Admin/RoleMenu/add');
                    $r->get(self::u('/clear'),'Admin/RoleMenu/clear');
                    $r->get(self::u('/find_tree'),'Admin/RoleMenu/findByTree');
                    $r->get(self::u('/list'),'Admin/RoleMenu/listRoleMenu');
                });

                $r->addGroup('/group',function($r){
                    $r->get(self::u('/g_admin'),'Admin/Group/gAdmin');
                    $r->get(self::u('/g_index'),'Admin/Group/gIndex');
                    $r->get(self::u('/admin_add'),'Admin/Group/adminAdd');
                    $r->post(self::u('/admin_save'),'Admin/Group/adminSave');
                    $r->get(self::u('/admin_del'),'Admin/Group/adminDel');
                    $r->post(self::u('/admin_add_pri'),'Admin/Group/adminAddPri');//为后台用户组增加某些权限
                    $r->post(self::u('/admin_del_pri'),'Admin/Group/adminDelPri'); //删除后台用户组的某些权限
                    $r->get(self::u('/role_menu'),'Admin/Group/adminRoleMenu'); //列出后台用户组当前的权限菜单
                    $r->post(self::u('/role_menu_save'),'Admin/Group/adminRoleMenuSave');//保存用户组权限菜单的修改
                    $r->post(self::u('/role_menu_ord'),'Admin/Group/adminRoleMenuOrd');//保存用户组菜单的排序修改
                });

                $r->addGroup('/pri',function($r){
                    $r->get(self::u('/group_hasnt_pri'),'Admin/PriAdmin/hasntPri');
                    $r->get(self::u('/group_has_pri'),'Admin/PriAdmin/hasPri');
                    $r->get(self::u('/admin'),'Admin/PriAdmin/admin');
                    $r->get(self::u('/index'),'Admin/PriAdmin/index');
                    $r->post(self::u('/save'),'Admin/PriAdmin/save');
                    $r->get(self::u('/buil_my_pris'),'Admin/PriAdmin/builMyPriUrls');
                    $r->get(self::u('/del_admin_pri'),'Admin/PriAdmin/delAdminPri');//删除后台用户组权限
                });


                $r->addGroup('/log',function($r){
                    $r->get(self::u('/system_error'),'Admin/Log/systemError');
                    $r->post(self::u('/system_error_del'),'Admin/Log/delSystemError');
                });

                $r->addGroup('/oplog',function($r){
                    $r->get(self::u('/admin'),'Admin/Oplog/admin'); //后台用户操作日志
                    $r->post(self::u('、admin_del'),'Admin/Oplog/adminDel'); //删除操作日志
                });

                $r->addGroup('/login_log',function($r){
                    $r->get(self::u('/admin'),'Admin/LoginLog/admin');
                    $r->post(self::u('/admin_del'),'Admin/LoginLog/adminDel');
                });

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