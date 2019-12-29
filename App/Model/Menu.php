<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/22 下午7:23
 * class:Menu.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use EasySwoole\ORM\AbstractModel;

class Menu extends AbstractModel
{
    protected $connectionName = 'read__';
    protected $tableName = 'menu';
    protected $autoTimeStamp = false;
}