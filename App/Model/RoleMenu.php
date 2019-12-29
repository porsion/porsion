<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/24 下午4:23
 * class:RoleMenu.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\ORM\AbstractModel;

class RoleMenu extends AbstractModel
{

    protected $connectionName = 'read__';
    protected $tableName = 'role_menu';
    protected $autoTimeStamp = false;

    /**
     * @param RoleMenu $mode
     * @param int $lastInsertId
     * 新增之后的事件
     */
    protected static function onAfterInsert( self $mode,int $lastInsertId )
    {
        TaskManager::getInstance()->async(function() use ($lastInsertId){
                $arr = ['group_id' => 1 , 'role_menu_id' => $lastInsertId];
                GroupMenuMap::create()->connection('write')->data($arr)->save();
        });
    }
}