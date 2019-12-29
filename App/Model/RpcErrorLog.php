<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/24 下午12:35
 * class:RpcErrorLog.php
 * Project:YoSoos
 *
 */

namespace App\Model;


use EasySwoole\ORM\AbstractModel;

class RpcErrorLog extends AbstractModel
{
    protected $connectionName = 'write';
    protected $tableName = 'rpc_error_log';
    protected $autoTimeStamp = false;

}