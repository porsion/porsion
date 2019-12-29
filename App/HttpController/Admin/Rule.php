<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/28 上午11:00
 * class:Rule.php
 * Project:YoSoos
 *
 */

namespace App\HttpController\Admin;

use App\Logic\Rule as RuleLogic;

final class Rule extends Base
{
        public function index()
        {
            $data = RuleLogic::findAll($this->request());
            return $this->lay($data['data'],$data['rows']);
        }

       /* public function find()
        {
            $id = (int)$this->request()->getQueryParam('id');
            if( $id <= 0  ) return $this->argError();
            return $this->success( RuleLogic::findOne($id) );
        }*/

        /**
         * @return mixed
         * 保存新增或修改
         */
        public function save()
        {
            $data = $this->request()->getParsedBody('data');
            if( !$data ) return $this->argError();
            if( RuleLogic::save($data) )
            {
                return $this->success();
            }
            return $this->err();
        }
}