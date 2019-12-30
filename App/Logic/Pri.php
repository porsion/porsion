<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/28 下午4:06
 * class:Pri.php
 * Project:YoSoos
 *
 */

namespace App\Logic;
use App\Model\GroupPriMap;
use App\Model\Privileges as PriModel;
use App\Model\GroupPriMap as GroupPriMapModel;
use App\Util\Common;
use EasySwoole\Http\Request;
use EasySwoole\ORM\Exception\Exception;

class Pri
{
    /**
     * @param int $group_id
     * @param bool $has
     * @param array $limit ['limit'=>10,'page' => 1]
     *
     * @param int $role_menu_id
     *
     * @return array
     * @throws Exception
     * @throws \Throwable
     */
    public static function getGroupPri( int $group_id, bool $has = false , array $limit, $role_menu_id = 0) :array
    {
        if( $has  )
        {
            $where  = "`map`.`group_id` = $group_id";
            if( (int)$role_menu_id > 0 )
            {
                $where .= " AND `pr`.`role_menu_id` = $role_menu_id";
            }
            return self::getPrisWithGroup($where,$limit);
        }
        else
        {
            return self::getPrisWithoutGroup($group_id, $limit, (int)$role_menu_id);
        }


    }

    /**
     * @param Request $req
     *
     * @return array
     * 列出所有的后台权限，如果有条件则根本条件获取
     * @throws Exception
     * @throws \Throwable
     */
    public static function pris(Request $req)
    {
            $limit = Common::limit($req);
            $key = $req->getQueryParam('key');
            $role_menu_id = (int) $req->getQueryParam('role_menu_id');
            $wh = [];
            if( $key )
            {
                $wh =   ['pr.desc' => ['%'.$key.'%','like'],'pr.url'=> ['%'.$key.'%','like','OR']];;
            }
            if( $role_menu_id > 0 )
            {
                $wh += ['pr.role_menu_id'=> $role_menu_id];
            }
            return self::getPris($wh,$limit);
    }

    /**
     * @param Request $req
     *
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws Exception
     * @throws \Throwable
     * 保存新增或修改后的权限
     */
    public static function save( Request $req) : bool
    {
        $data = $req->getParsedBody('data');
        $mode = PriModel::create()->connection('write');
         if( isset($data['url']) && $data['url']) $data['url'] = trim($data['url'],'/');
        if( isset($data['auto_id']) && $data['auto_id'] > 0)
        {
            $ret = (int)$mode->update($data);
        }
        else
        {
            $ret = (int)$mode->data($data)->save();
        }
        return $ret > 0;
    }


    /**
     * @param int $group_id
     *
     * @return array|null
     * @throws Exception
     * @throws \Throwable
     * 根据group_id获取后台用户权限urls
     */
    public static function getPrisUrlByGroupId( int $group_id) : ? array
    {
        $role = GroupPriMap::create()->alias('g')->where('g.group_id',$group_id)
            ->field('p.url')
            ->join('privileges p','g.pri_id = p.auto_id')
            ->findAll();
        return array_column($role,'url');
    }


    /**
     * @param array $where
     * @param array $limit
     *
     * @return array
     * @throws Exception
     * @throws \Throwable
     * 获取所有权限 with role_menu
     */
    private static function getPris($where = [],array $limit)
    {
        $mode = PriModel::create()->alias('pr')->limit($limit['page'],$limit['limit']);
        if( $where )
        {
            $mode->where($where);
        }
        $mode->order('pr.auto_id','desc')
            ->field('pr.auto_id,pr.desc,pr.url,m.title,pr.role_menu_id')
           // ->join('privileges pr','map.pri_id = pr.auto_id','LEFT')
            ->join('role_menu m','m.auto_id = pr.role_menu_id','LEFT')
            ->withTotalCount();
        $data = $mode->findAll();
       // var_dump($mode->lastQuery()->getLastQuery());
        $rows = $mode->lastQueryResult()->getTotalCount();
        return [$data,$rows];
    }

    /**
     * @param $where
     * @param array $limit
     *
     * @return array
     * @throws Exception
     * @throws \Throwable
     * 获取某个组所拥有的权限
     */
    private static function getPrisWithGroup($where , array $limit)
    {
        $mode = GroupPriMapModel::create()->alias('map')->limit($limit['page'],$limit['limit']);
        if( $where )
        {
            $mode->where($where);
        }
        $mode->order('map.auto_id','desc')
            ->field('pr.auto_id,pr.desc,pr.url,m.title,pr.role_menu_id,map.auto_id as map_auto_id')
             ->join('privileges pr','map.pri_id = pr.auto_id','LEFT')
            ->join('role_menu m','m.auto_id = pr.role_menu_id','LEFT')
            ->withTotalCount();
        $data = $mode->findAll();
      //   var_dump($mode->lastQuery()->getLastQuery());
        $rows = $mode->lastQueryResult()->getTotalCount();
        return [$data,$rows];
    }

    /**
     * @param int $group_id
     * @param array $limit
     * @param int $role_menu_id
     *
     * @return array
     * @throws Exception
     * @throws \Throwable
     * 获取某个用户组不拥有的权限
     */
    private static function getPrisWithoutGroup( int $group_id,array $limit,int $role_menu_id)
    {
        $mode = GroupPriMap::create()->where('group_id',$group_id);
        $pri_ids = $mode->column('pri_id');
        $priMode = PriModel::create()->limit($limit['page'],$limit['limit']);
        if( $pri_ids )
        {
            $priMode->where('auto_id',$pri_ids,'not in');
        }
        if( $role_menu_id > 0 )
        {
            $priMode->where('role_menu_id',$role_menu_id);
        }
        $data = $priMode->withTotalCount()->order('auto_id')->findAll();
        $rows = $priMode->lastQueryResult()->getTotalCount();
        return [$data,$rows];
    }
}