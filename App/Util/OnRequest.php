<?php
namespace App\Util;
use App\Exception\TokenException;
use App\Exception\LoginException;
trait OnRequest 
{

    /**
     * 请求之前的token验证
     *
     * @param \EasySwoole\Http\Request $req
     * @param string $mode 前台还是后台
     * @throws TokenException
     */
    public function verifyToken( \EasySwoole\Http\Request $req , string $mode) : bool
    {
        $t = true;
    
        if( $t )
        {
            return true;
        }
        throw new TokenException();
    }


    /**
     * 请求之前的登录验证
     *
     * @param \EasySwoole\Http\Request $req
     * @param string $mode 前台还是后台
     * @throws LoginException
     */
    public function verifyLogin( \EasySwoole\Http\Request $req,string $mode ) 
    {
            return true;
    }




}