<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/30 上午9:59
 * class:Oplog.php
 * Project:YoSoos
 *
 */

namespace App\HttpController\Admin;


use App\Rpc\Log\AdminOplog;

final class Oplog extends Base
{

    public function admin()
    {
        $data = AdminOplog::listAdminOplog($this->request());
        return $this->lay(...$data);
    }

    /**
     * 删除后台用户操作日志
     */
    public function adminDel()
    {

    }
}