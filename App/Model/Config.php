<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/22 下午6:38
 * class:Config.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use EasySwoole\ORM\AbstractModel;

class Config extends AbstractModel
{

    protected $connectionName = 'read__';
    protected $tableName = 'system_config';
    protected $autoTimeStamp = false;
}