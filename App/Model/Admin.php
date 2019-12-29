<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/22 下午2:43
 * class:Admin.php
 * Project:YoSoos
 *
 */

namespace App\Model;


class Admin extends \EasySwoole\ORM\AbstractModel
{
    protected $connectionName = 'read__';
    protected $tableName = 'admin';
    protected $autoTimeStamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'last_login_time';


}