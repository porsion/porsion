<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/22 下午6:24
 * class:Group.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use EasySwoole\ORM\AbstractModel;

class Group extends AbstractModel
{
    protected $connectionName = 'read__';
    protected $tableName = 'adm_group';
    protected $autoTimeStamp = false;


}