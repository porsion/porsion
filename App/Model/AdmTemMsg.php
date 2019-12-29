<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/22 下午7:16
 * class:AdmTemMsg.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use EasySwoole\ORM\AbstractModel;

class AdmTemMsg extends AbstractModel
{

    protected $connectionName = 'read__';
    protected $tableName = 'admin_team_msg';
    protected $autoTimeStamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'read_time';
}