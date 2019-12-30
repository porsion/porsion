<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/30 下午2:38
 * class:LoginLog.php
 * Project:YoSoos
 *
 */

namespace App\HttpController\Admin;
use App\Rpc\Log\LoginLog as AdmLoginLog;

final class LoginLog extends Base
{

    /**
     * 后台用户登录日志列表
     */
    public function admin()
    {
            $data = AdmLoginLog::AdminLoginLog($this->request());
            return $this->lay(...$data);
    }

    /**
     * @return mixed
     * 删除后台用户登录日志
     */
    public function adminDel()
    {
        $ret = AdmLoginLog::adminLoginLogDel($this->request());
        if($ret > 0)
            return $this->success();
        return $this->delError();
    }
}