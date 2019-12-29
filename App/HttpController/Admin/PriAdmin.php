<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/28 下午4:01
 * class:Pri.php
 * Project:YoSoos
 *
 */

namespace App\HttpController\Admin;


use App\Logic\Pri;
use App\Model\Privileges;
use App\Util\Common;
use App\Logic\Pri as PriLogic;
use App\Util\Constans;
use EasySwoole\ORM\Exception\Exception;

class PriAdmin extends Base
{

        /**
         * 列出所有权限
         */
        public function admin()
        {
               $data = PriLogic::pris($this->request());
               return $this->lay(...$data);
        }

    /**
     * @return mixed
     * @throws Exception
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \Throwable
     * 保存一个权限
     */
        public function save()
        {
            $ret = PriLogic::save($this->request());
            if( $ret )
            {
                return $this->success();
            }
            return $this->err('没变化的数据或有重复url');
        }

    /**
     * @return mixed
     * @throws Exception
     * @throws \Throwable
     * 重建我的权限缓存 for redis
     */
        public function builMyPriUrls()
        {
            $group_id = Common::getUserData('group_id');
            $roles = Pri::getPrisUrlByGroupId($group_id);
            Common::redis()->del(Constans::REDIS_ADMIN_ROLE . Common::getUid(true));
            Common::redis()->sAdd(Constans::REDIS_ADMIN_ROLE . Common::getUid(true),...$roles);
            return $this->success();
        }


    /**
     * @return mixed
     * @throws Exception
     * @throws \Throwable
     * 删除一个后台权限
     */
        public function delAdminPri()
        {
            $id = $this->request()->getQueryParam('id');
            if( !$id ) return $this->argError();
            $mode = Privileges::create()->connection('write');
            $ret = $mode->destroy($id);
            if( $ret === 0  )
                return $this->delError('删除失败！可能有组正在使用该权限');
            return $this->success();
        }


    /**
     * @throws Exception
     * @throws \Throwable
     * 获取我这个组不拥有的权限
     */
        public function hasntPri()
        {
            $group_id = (int)$this->request()->getQueryParam('id');
            if( !$group_id ) return $this->argError();
            $role_menu_id = $this->request()->getQueryParam('role_menu_id');
            $limit = Common::limit($this->request());
            $data = PriLogic::getGroupPri($group_id,false,$limit,(int)$role_menu_id);
            return $this->lay(...$data);

        }

    /**
     * @throws Exception
     * @throws \Throwable
     * 获取我这个组所拥有的权限
     */
        public function hasPri()
        {
            $group_id = (int)$this->request()->getQueryParam('id');
            if( !$group_id ) return $this->argError();
            $limit = Common::limit($this->request());
            $role_menu_id = $this->request()->getQueryParam('role_menu_id');
            $data = PriLogic::getGroupPri($group_id,true,$limit,(int)$role_menu_id);
            return $this->lay(...$data);
        }
}