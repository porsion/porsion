<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/23 下午6:16
 * class:System.php
 * Project:YoSoos
 *
 */

namespace App\HttpController\Admin;

use App\Logic\System as SystemLogic;
use App\Util\Common;
class System extends Base
{
    /**
     * 设置首页
     */
        public function set()
        {
            $data = SystemLogic::findAll(null,true);
              return $this->success($data);
        }

    /**
     * @return mixed
     * 设置某些开关
     */
        public function setSwitch()
        {
            $data = $this->request()->getParsedBody('data');
            SystemLogic::saveSigle($data);
            return $this->success();
        }

        public function list()
        {
            $limit = Common::limit($this->request());
            $where = [];
            if( $key = $this->request()->getQueryParam('key') )
            {
                $where = ['desc' => ['%'.$key.'%','like'],'k'=> ['%'.$key.'%','like','OR']];
            }
            $mode = SystemLogic::model()->where($where)->limit($limit['page'], $limit['limit'])->withTotalCount();
            $data = $mode->all();
           // var_dump($mode->lastQuery()->getLastQuery());
            $result = $mode->lastQueryResult();
            $rows = $result->getTotalCount();
           return $this->lay($data,$rows);
        }

    /**
     * 保存某一个修改或新增的配置项
     */
        public function save()
        {
            $data = $this->request()->getParsedBody('data');
            $ret = SystemLogic::update($data);
            if($ret) return $this->success();
            else return $this->err('有重复url');
        }


    /**
     * 清空redis里的配置缓存
     * 并重新生成
     */
        public function clear()
        {
            SystemLogic::clearRedisConfig();
            SystemLogic::findAll();
            return $this->success();
        }

}