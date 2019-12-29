<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/28 下午12:40
 * class:Log.php
 * Project:YoSoos
 *
 */

namespace App\HttpController\Admin;

use App\Rpc\Log\SystemError;
final class Log extends Base
{
    public function systemError()
    {
        $data = SystemError::exceptionLog($this->request());
        if( $data )
        {
            return $this->lay(...$data);
        }
        return $this->err();
    }

    public function delSystemError()
    {
        $id = $this->request()->getParsedBody('id');
        $ids = $this->request()->getParsedBody('ids');
        $argId = null;
        if( !$id && !$ids )
            return $this->argError();
        if( $id )
        {
            $argId = [$id];
        }
        else if ($ids)
        {
            $argId = $ids;
        }
        $ret = SystemError::delExceptionLog($argId);
        if( $ret['status'] )
        {
            return $this->success();
        }
        return $this->err();

    }

}