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

class RoleMenu
{
    final  public static function list( Request $req)
    {
        $limit = Common::limit($req);
        try {
            $mode = Rmenu::create()->withTotalCount();
            $data = $mode->all(null,true);
            $result = $mode->lastQueryResult();
            $rows = $result->getTotalCount();
            return ['data'=>$data,'rows'=>$rows];
        }  catch (\Throwable $e) {
            throw $e;
        }

    }

    final public static function findOne( int $id): ? array
    {
        $data = Rmenu::create()->findOne($id);
        $all_data = Rmenu::create()->findAll();
        return   ['find_data' => $data ,'all_data' => Common::create_menu( $all_data,'children' )];
    }

    final public static function updateOrInsert( array $data)
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

    final public static function findAll( Request $req)
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
     * @throws \Throwable
     * 获取某个用户组的权限列表
     */
    public static function findGroupMenu(  int $group_id) : array
    {
        $mode = GroupMenuMap::create()->alias('g')->where('g.group_id',$group_id)
            ->field('m.auto_id,m.href,m.title,m.pid,m.icon')->order('g.ord','desc')
            ->join('role_menu m','m.auto_id = g.role_menu_id');
        return $mode->findAll();
    }


    public static function findByTree(Request $req)
    {
        $data = Rmenu::create()->findAll(null,true);
       // $group_menu_ids = array_diff( $group_menu_ids, array_unique( array_filter( array_column($data,'pid') ) ) );
       // $ret = ['all_menu' => \create_menu($data,'children'),'group_menu_ids' => $group_menu_ids];
      return Common::create_menu($data,'children');
    }
}