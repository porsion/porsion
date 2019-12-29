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
use App\Model\GroupPriMap;
use App\Rpc\Log\AdminOplog;
use App\Util\Common;
use App\Model\AdminGroup as AdminGroupModel;
use EasySwoole\Http\Request;
use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\Exception\Exception;

final class AdminGruop
{

    /**
     * @param Request $req
     *
     * @return array
     * @throws \Throwable
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
        }  catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * @param int $auto_id
     *
     * @return AdminGroupModel|array|bool|AbstractModel|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws Exception
     * @throws \Throwable
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
     * @throws \Throwable
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
     * @throws \Throwable
     * 删除用户组
     */
    public static function del(int $auto_id,int $rule_id)
    {
        $ret = AdminGroupModel::create()->connection('write')->destroy($auto_id);
        if( $ret > 0 )
        {
            AdminOplog::adminGroup($auto_id,AdminGroupModel::create()->getTableName(),'delete',$rule_id);
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
     * @throws \Throwable
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
        return $ret;


    }
}