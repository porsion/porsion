<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/25 下午6:44
 * class:Art.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use EasySwoole\ORM\AbstractModel;

class Art extends AbstractModel
{
    protected $connectionName = 'read__';
    protected $tableName = 'art';
    protected $autoTimeStamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';


    protected static function onBeforeInsert(self $model )
    {


    }

    protected static function onAfterInsert( self $model,int $lastInsertId )
    {

    }


    protected static function onBeforeUpdate( self $model,$BeforeUpdateData )
    {

    }

    protected static function onAfterUpdate(self $mode,$afterUpdateData)
    {

    }

    protected static function onBeforeDelete(self $mode, $deleteId)
    {

    }

    protected static function onAfterDelete(self $mode, $deleteId)
    {

    }

}