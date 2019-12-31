<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/27 下午12:48
 * class:AdminGroup.php
 * Project:YoSoos
 *
 */

namespace App\Model;
use EasySwoole\ORM\AbstractModel;
use App\Rpc\Log\AdminOplog;
use EasySwoole\ORM\Exception\Exception;

class AdminGroup extends AbstractModel
{

    protected $connectionName = 'read__';
    protected $tableName = 'adm_group';
    protected $autoTimeStamp = false;


    /**
     * @param AdminGroup $model
     * 新增之前的事件
     */
    protected static function onBeforeInsert(self $model )
    {


    }

    /**
     * @param AdminGroup $mode
     * @param int $lastInsertId
     * 新增之后的事件
     *
     * @throws Exception
     */
    protected static function onAfterInsert( self $mode,int $lastInsertId )
    {
        $param = [
            ( $mode->schemaInfo()->getPkFiledName() ?? 'auto_id') => $lastInsertId
        ];
        AdminOplog::insAdminOplog($param,$mode->getTableName(),'insert');
    }


    /**
     * @param AdminGroup $model
     * @param $BeforeUpdateData
     * 修改之前的事件
     */
    protected static function onBeforeUpdate( self $model,$BeforeUpdateData )
    {

    }

    /**
     * @param AdminGroup $mode
     * @param $afterUpdateData
     * 修改之后的事件
     *
     */
    protected static function onAfterUpdate(self $mode,$afterUpdateData)
    {
        AdminOplog::insAdminOplog($afterUpdateData,$mode->getTableName(),'update');
    }

    /**
     * @param AdminGroup $mode
     * @param $deleteId
     * 删除之前的事件
     *
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    protected static function onBeforeDelete(self $mode, $deleteId)
    {
            $has = Admin::create()->where('group_id',$deleteId)->count();
            if( $has > 0 )
            {
                return false;
            }
    }


    /**
     * @param AdminGroup $mode
     * @param $deleteId
     * 删除之后的事件
     */
    protected static function onAfterDelete(self $mode, $deleteId)
    {

        /**
         * 已废弃，因为写删除日志需要rule_id，此处无法接收到rule_id
         */
       // AdminOplog::insAdminOplog($deleteId,$mode->getTableName(),'delete');
    }

}