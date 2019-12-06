<?php
namespace App\Util;
trait HttpStatus 
{


    
    protected function noAuth( $msg = '没有相关权限' )
    {
            return $this->writeJson(HttpStatusCode::NO_AUTH,null,$msg);
    }



    protected function success($data = null,?string $msg = null )
    {
        if( \is_null( $data ) )
        {
            $msg = '操作成功！';
            $data = null;
            
        }
        else if( \is_string($data) )
        {
            $msg = $data;
            $data = null;
            
        }
        else if( \is_array($data))
        {
            $msg = $msg ?: '操作成功！';
        }
        else if( \is_object( $data ) )
        {
            $data = \get_object_vars($data);
            $msg = '操作成功！';
        }
        return $this->writeJson(HttpStatusCode::SUCCESS,$data,$msg);
    }

    protected function noLogin( $msg = '您没有登录或登录已失效！')
    {
            return $this->writeJson(HttpStatusCode::NO_LOGIN,null,$msg);
    }


    protected function argError( $msg = '参数错误！')
    {
        return $this->writeJson(HttpStatusCode::ARG_ERR,null,$msg);
    }
    

    protected function err( $msg = '服务器错误！')
    {
        return $this->writeJson(HttpStatusCode::ERR,null,$msg);
    }
}