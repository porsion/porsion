<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/27 下午9:18
 * class:Rule.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use App\Rpc\Log\AdminOplog;
use EasySwoole\ORM\AbstractModel;

class Rule extends AbstractModel
{
    protected $connectionName = 'read__';
    protected $tableName = 'rule';
    protected $autoTimeStamp = true;


    protected function getCreateTimeAttr($v)
    {
        return date('Y-m-d H:i',$v);
    }

    protected function getUpdateTimeAttr($v)
    {
        return date('Y-m-d H:i',$v);
    }
    /**
     * @param Rule $mode
     * @param int $lastInsertId
     * 新增之后的事件
     */
    protected static function onAfterInsert( self $mode,int $lastInsertId )
    {

    }

    /**
     * @param Rule $mode
     * @param $deleteId
     * 删除之后的事件 rpc远程写日志
     */
    protected static function onAfterDelete(self $mode, $deleteId)
    {
        AdminOplog::adminGroup($deleteId,$mode->getTableName(),'delete');
    }


    /**
     * @param Rule $mode
     * @param $deleteId
     * 删除之前的事件 删除之前，要检查是否有表在使用该id
     */
    protected static function onBeforeDelete(self $mode, $deleteId)
    {

    }

    /**
     * @param Rule $mode
     * @param $afterUpdateData
     * 修改之后的事件
     */
    protected static function onAfterUpdate(self $mode,$afterUpdateData)
    {

    }
}