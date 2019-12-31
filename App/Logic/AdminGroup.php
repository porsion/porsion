<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/27 下午12:50
 * class:AdminGruop.php
 * Project:YoSoos
 *
 */

namespace App\Logic;
use App\Model\GroupMenuMap;
use App\Model\GroupPriMap;
use App\Rpc\Log\AdminOplog;
use App\Util\Common;
use App\Model\AdminGroup as AdminGroupModel;
use EasySwoole\Http\Request;
use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\Exception\Exception;
use Throwable;

final class AdminGroup
{

    /**
     * @param Request $req
     *
     * @return array
     * @throws Throwable
     * 列出所有后台用户组
     */
    public static function findAll( Request $req)
    {
        $limit = Common::limit($req);
        try {
            $mode = AdminGroupModel::create()->withTotalCount();
            $data = $mode->limit($limit['page'],$limit['limit'])->all(null,true);
            $result = $mode->lastQueryResult();
            $rows = $result->getTotalCount();
            return ['data'=>$data,'rows'=>$rows];
        }  catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * @param int $auto_id
     *
     * @return AdminGroupModel|array|bool|AbstractModel|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws Exception
     * @throws Throwable
     * 查找一个用户组
     */
    public static function findOne(int $auto_id)
    {
        return AdminGroupModel::create()->get($auto_id,true);
    }


    /**
     * @param array $data
     *
     * @return bool|int|null
     * @throws Exception
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws Throwable
     * 新增或更新一个用户组
     */
    public static function update(array $data)
    {
        $mode = AdminGroupModel::create()->connection('write');
        if( isset($data['read_only_login']) && $data['read_only_login'] == 'on' )
            $data['read_only_login'] = 'y';
        else
            $data['read_only_login'] = 'n';
        if( !$data['auto_id'] )
        {
            unset($data['auto_id']);
            return $mode->data($data)->save();
        }
        return $mode->update($data);

    }

    /**
     * @param int $auto_id
     * @param int $rule_id
     *
     * @return bool
     * @throws Exception
     * @throws Throwable
     * 删除用户组
     */
    public static function del(int $auto_id,int $rule_id)
    {
        $ret = AdminGroupModel::create()->connection('write')->destroy($auto_id);
        if( $ret > 0 )
        {
            $param = [
                ( AdminGroupModel::create()->schemaInfo()->getPkFiledName() ?? 'auto_id') => $auto_id
            ];
            AdminOplog::insAdminOplog($param,AdminGroupModel::create()->getTableName(),'delete',$rule_id);
        }
        return $ret > 0;
    }


    /**
     * @param Request $req
     * @param string $type
     * 操作用户组权限
     *
     * @return bool|int|null
     * @throws Exception
     * @throws Throwable
     */
    public static function actPri(Request $req,string $type = 'add')
    {
        $pri_ids = $req->getParsedBody('ids');
        $group_id = $req->getParsedBody('group_id');
        $ret = null;
        if( $type == 'add' )
        {
            if( is_array($pri_ids) )
            {
                foreach ($pri_ids as $pri_id)
                {
                  $ret =  GroupPriMap::create(['pri_id'=>(int)$pri_id,'group_id'=>(int)$group_id])
                        ->connection('write')->save();
                  if( $ret <= 0 ) break;
                }
            }
            else
            {
               $ret = GroupPriMap::create(['pri_id'=>(int)$pri_ids,'group_id'=>(int)$group_id])
                   ->connection('write')->save();
            }
        }
        else if( $type == 'del' )
        {
            $ret = GroupPriMap::create()->connection('write')->destroy($pri_ids);
        }
        if( $ret )
        {
            $param = [
               'group_id'   => $group_id,
                'pri_id'    => $pri_ids,
            ];
            AdminOplog::insAdminOplog($param,GroupPriMap::create()->getTableName(),$type == 'add' ? 'insert' : 'delete');
        }
        return $ret;
    }


    /**
     * @param int $group_id
     *
     * @return array
     * @throws Exception
     * @throws Throwable
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * 根据用户组id查找用户当前的权限菜单id
     * 使用场景：修改用户组权限菜单时，需要查看该组当前的权限菜单是什么情况
     */
    public static function findRoleMenuIdByGroupId( int $group_id) : array
    {
        return GroupMenuMap::create()->where('group_id',$group_id)->order('ord','desc')
            ->column('role_menu_id');
    }

    /**
     * @param mixed ...$where
     *
     * @return int|null
     * @throws Exception
     * @throws Throwable
     * 删除用户组与权限菜单的关系
     */
    public static function delGroupMenuMap( ...$where)
    {
        return GroupMenuMap::create()->connection('write')->where(...$where)
            ->destroy();
    }


    /**
     * @param array $role_menu_ids
     * @param int $group_id
     *
     * @return int
     * @throws Exception
     * @throws Throwable
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * 保存后台用户组权限菜单的修改
     */
    public static function saveGroupMenuMap(array $role_menu_ids,int $group_id) : int
    {
        $ret = 0;
        if(!$role_menu_ids)
        {
            $ret = self::delGroupMenuMap('group_id',$group_id);
        }
        else
        {
            $curr_group_role_menu_ids = self::findRoleMenuIdByGroupId($group_id);
            $insert_data = array_diff( $role_menu_ids , $curr_group_role_menu_ids);
            $delete_data = array_diff($curr_group_role_menu_ids,$role_menu_ids);
            if( $insert_data  )
            {
                foreach($insert_data as $v)
                {
                    $ret = GroupMenuMap::create([ 'role_menu_id' => $v, 'group_id'=> $group_id ])
                        ->connection('write')->save();
                    if( $ret <= 0 ) break;
                }
            }
            if( $delete_data )
            {
                $mode = GroupMenuMap::create()->connection('write')->where('role_menu_id',$delete_data,'IN');
                $ret = $mode->destroy();
            }
        }
        if( $ret > 0 )
        {
            $param = [
                'group_id'   => $group_id,
                'role_menu_id'    => $role_menu_ids,
            ];
            AdminOplog::insAdminOplog($param,GroupMenuMap::create()->getTableName(),'update');
        }
        return $ret;
    }
}