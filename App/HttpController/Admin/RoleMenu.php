<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/24 下午4:17
 * class:RoleMenu.php
 * Project:YoSoos
 * @package App\HttpController\Admin
 * 权限菜单管理
 */

namespace App\HttpController\Admin;

use App\Logic\RoleMenu as LogicRoleMenu;
use App\Util\Common;


class RoleMenu extends Base
{

    final public function index()
    {
        $data =  LogicRoleMenu::list($this->request());
        return $this->lay($data['data'],$data['rows']);
    }

    /**
     * @return mixed
     * 以带children的形式列出相关权限菜单
     */
    final public function add()
    {
        $data = LogicRoleMenu::findAll($this->request());
        return $this->success(Common::create_menu($data,'children'));
    }

    /**
     * @return mixed
     * 编辑某个权限菜单
     */
    final public function edit()
    {
        $id = $this->request()->getQueryParam('id');
        $data = LogicRoleMenu::findOne((int) $id);
        return $this->success($data);
    }

    /**
     * @return mixed
     * 保存某个菜单
     */
    final public function save()
    {
        $data = $this->request()->getParsedBody('data');
        if( $data )
        {
            $ret = LogicRoleMenu::updateOrInsert($data);
            if($ret)
            {
                return $this->success('菜单更新成功！');
            }
            return $this->err('菜单更新失败！');
        }
        return $this->err();
    }

    /**
     * 根据当前用户的组ID
     * 更新后台的用户组左侧菜单
     */
    final public function clear()
    {
        $group_id = Common::getUserData('group_id');
        $menu  = LogicRoleMenu::findGroupMenu($group_id);
        return $this->success(Common::create_menu($menu));
    }

    /**
     * 获取请求id的所有子集，dep为1
     */
    public function listRoleMenu()
    {
        $data = LogicRoleMenu::findAll($this->request());
        return $this->success($data);
    }

    /**
     * @return mixed
     * 获取树形式的左侧菜单
     */
    public function findByTree()
    {
        return $this->success(LogicRoleMenu::findByTree($this->request()));
    }
}