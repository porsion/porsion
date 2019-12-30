<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/24 下午4:22
 * class:RoleMenu.php
 * Project:YoSoos
 *
 */

namespace App\Logic;

use App\Model\GroupMenuMap;
use App\Model\RoleMenu as Rmenu;
use App\Util\Common;
use EasySwoole\Http\Request;
use EasySwoole\ORM\Exception\Exception;
use Throwable;

final class RoleMenu
{
    /**
     * @param Request $req
     *
     * @return array
     * @throws Throwable
     * 以列表的形式 列出后台用户组的权限菜单 带分页
     */
     public static function list( Request $req)
    {
        $limit = Common::limit($req);
        try {
            $mode = Rmenu::create()->withTotalCount()->limit($limit['page'],$limit['limit']);
            $data = $mode->all(null,true);
            $result = $mode->lastQueryResult();
            $rows = $result->getTotalCount();
            return ['data'=>$data,'rows'=>$rows];
        }  catch (Throwable $e) {
            throw $e;
        }

    }

    /**
     * @param int $id
     *
     * @return array|null
     * @throws Exception
     * @throws Throwable
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * 标记当前$id在所有roel_menu_id
     * 使用场景：标记某个权限的role_menu_id在role_menu里的位置
     */
    public static function findOne( int $id): ? array
    {
        $data = Rmenu::create()->findOne($id);
        $all_data = Rmenu::create()->findAll();
        return   ['find_data' => $data ,'all_data' => Common::create_menu( $all_data,'children' )];
    }

    /**
     * @param array $data
     *
     * @return bool|int|null
     * @throws Exception
     * @throws Throwable
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * 保存修改或新增的权限菜单
     */
    public static function updateOrInsert( array $data)
    {
        $insertModel = Rmenu::create()->connection('write');
        if(isset($data['auto_id']) && (int)$data['auto_id'] > 0)
        {
            $old_data = Rmenu::create()->findOne($data['auto_id']);
            $old_data = array_merge($old_data,$data);
            $ret = $insertModel->update($old_data,['auto_id' => $old_data['auto_id']]);
        }
        else
        {
            $ret = $insertModel->data($data)->save();
        }
        return $ret;
    }

    /**
     * @param Request $req
     *
     * @return array
     * @throws Exception
     * @throws Throwable
     *
     * 根据pid获取该pid下面的所有子集 不带分页
     */
    public static function findAll( Request $req)
    {
        $mode = Rmenu::create();
        $pid = $req->getQueryParam('id');
        if( is_numeric($pid) )
        {
            $mode->where('pid',(int)$pid);
        }
        return $mode->findAll();
    }

    /**
     * @param int $group_id
     *
     * @return array
     * @throws Exception
     * @throws Throwable
     * 获取某个用户组的权限列表
     */
    public static function findGroupMenu(  int $group_id) : array
    {
        $mode = GroupMenuMap::create()->alias('g')->where('g.group_id',$group_id)
            ->field('m.auto_id,m.href,m.title,m.pid,m.icon')->order('g.ord','desc')
            ->join('role_menu m','m.auto_id = g.role_menu_id');
        return $mode->findAll();
    }


    /**
     * @return array|bool|mixed|null
     * @throws Exception
     * @throws Throwable 以树的形式 列出所有的后台用户组权限菜单
     */
    public static function findByTree()
    {
        $data = Rmenu::create()->findAll(null,true);
        return Common::create_menu($data,'children');
    }
}