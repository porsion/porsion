<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/27 下午12:47
 * class:Group.php
 * Project:YoSoos
 *
 */

namespace App\HttpController\Admin;


use App\Logic\AdminGroup as AdminGroupLogic;
use App\Model\AdminGroup;
use \Swoole\Coroutine\Channel;
use App\Model\GroupPriMap;
use EasySwoole\ORM\Exception\Exception;
use App\Logic\RoleMenu as RoleMenuLogic;
use Throwable;
use function array_diff;

final class Group extends Base
{
        /**
         * 前台用户组管理
         */
       public function gIndex()
        {

        }

    /**
     * 后台用户组
     */
    public function gAdmin()
    {
            $data = AdminGroupLogic::findAll($this->request());
            return $this->lay($data['data'],$data['rows']);
    }


    /**
     * 新增或修改后台用户组
     */
    public function adminAdd()
    {
        $id = (int)$this->request()->getQueryParam('id');
        if( $id > 0 )
        {
             $data = AdminGroupLogic::findOne($id);
             return $this->success($data);
        }
        return $this->argError();
    }

    /**
     * 保存修改或新增的用户组
     */
    public function adminSave()
    {
        $data = $this->request()->getParsedBody('data');
        AdminGroupLogic::update($data);
        return $this->success();
    }

    /**
     * @return mixed
     * 删除一个用户组
     * @throws Exception
     * @throws Throwable
     */
    public function adminDel()
    {
            $auto_id = (int) $this->request()->getQueryParam('id');
            $rule_id = (int) $this->request()->getQueryParam('rule_id');
            if( $auto_id > 0 && $rule_id > 0)
            {
                $ret = AdminGroupLogic::del($auto_id,$rule_id);
                if($ret === false)
                {
                    return $this->delError('不能删除！此用户组还有人在使用！');
                }
                return $this->success();
            }
            return $this->argError();
    }

    /**
     * 为后台用户组增权限
     */
    public function adminAddPri()
    {
           $ret = AdminGroupLogic::actPri($this->request(),'add');
           if( $ret > 0 ) return $this->success();
           return $this->err();
    }

    /**
     * 删除后台用户组的某些权限
     */
    public function adminDelPri()
    {
        $map_aotu_id = $this->request()->getParsedBody('ids');
        if(!$map_aotu_id) return $this->argError();
        $ret = GroupPriMap::create()->connection('write')->destroy($map_aotu_id);
        if( $ret > 0 )
            return $this->success();
        return $this->err();
    }


    /**
     * 列出后台用户组当前的权限菜单
     */
    public function adminRoleMenu()
    {
        $group_id = (int)$this->request()->getQueryParam('group_id');
        if( $group_id <= 0 ) return $this->argError();
        $data = [];
        $chan = new Channel(2);
        go(function() use ($chan){
            $chan->push(['all_role_menu' =>  RoleMenuLogic::findByTree()]);
        });
        go(function() use ($chan,$group_id){
            $group_role_menu_ids = AdminGroupLogic::findRoleMenuIdByGroupId($group_id);
            $all_data = \App\Model\RoleMenu::create()->column('pid');
            $chan->push(['group_role_menu_ids' =>
                array_diff( $group_role_menu_ids, array_unique( array_filter( $all_data) ) )]);
        });
        for($i=0;$i < 2;$i++)
        {
            $data += $chan->pop(3);
        }
        return $this->success($data);
    }


    /**
     * @return mixed
     * @throws Exception
     * @throws Throwable
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * 保存用户组权限菜单的修改
     */
    public function adminRoleMenuSave()
    {
        $ids = $this->request()->getParsedBody('ids');
        $group_id = $this->request()->getParsedBody('group_id');
        if( !$group_id ) return $this->argError();
        $is_has_group_id = AdminGroup::create()->get($group_id);
        if( !$is_has_group_id ) return $this->argError();
        $ret = AdminGroupLogic::saveGroupMenuMap((array)$ids,$group_id);
        if( $ret > 0 )
        {
            return $this->success();
        }
        return $this->err();
    }
}