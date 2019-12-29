<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/28 下午4:07
 * class:Privileges.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use EasySwoole\ORM\AbstractModel;
use EasySwoole\ORM\Exception\Exception;

class Privileges extends AbstractModel
{

    protected $connectionName = 'read__';
    protected $tableName = 'privileges';
    protected $autoTimeStamp = false;
    protected $updateTime = false;


    protected static function onBeforeInsert(self $model )
    {
        $is_has = $model->where('url',$model->toArray()['url'])->count();
         if( $is_has > 0 ) return false;

    }


    /**
     * @param Privileges $model
     * @param $BeforeUpdateData
     * 修改之前
     *
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    protected static function onBeforeUpdate( self $model,$BeforeUpdateData )
    {
           $is_has = (int) $model->where('auto_id',$BeforeUpdateData['auto_id'],'<>')->where('url',$BeforeUpdateData['url'])
                ->count();
           if( $is_has > 0 )
               return false;
    }

    /**
     * @param Privileges $mode
     * @param $deleteId
     * 删除之前的事件 删除之前，要检查是否有表在使用该id
     *
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    protected static function onBeforeDelete(self $mode, $deleteId)
    {
           $is_has = GroupPriMap::create()->where('pri_id',$deleteId)->count();
           if( $is_has > 0 )
               return false;
    }


}