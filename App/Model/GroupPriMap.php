<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/22 下午7:24
 * class:GroupPriMap.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use EasySwoole\ORM\AbstractModel;

class GroupPriMap extends AbstractModel
{

    protected $connectionName = 'read__';
    protected $tableName = 'group_pri_map';
    protected $autoTimeStamp = false;
    protected $updateTime = false;
}