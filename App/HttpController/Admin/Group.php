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


use App\Logic\AdminGruop;
use App\Model\GroupPriMap;

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
            $data = AdminGruop::findAll($this->request());
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
             $data = AdminGruop::findOne($id);
             return $this->success($data);
        }
    }

    /**
     * 保存修改或新增的用户组
     */
    public function adminSave()
    {
        $data = $this->request()->getParsedBody('data');
        AdminGruop::update($data);
        return $this->success();
    }

    /**
     * @return mixed
     * 删除一个用户组
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function adminDel()
    {
            $auto_id = (int) $this->request()->getQueryParam('id');
            $rule_id = (int) $this->request()->getQueryParam('rule_id');
            if( $auto_id > 0 && $rule_id > 0)
            {
                $ret = AdminGruop::del($auto_id,$rule_id);
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
           $ret = AdminGruop::actPri($this->request(),'add');
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
}