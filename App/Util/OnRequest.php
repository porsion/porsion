<?php
namespace App\Util;
use App\Exception\RoleException;
use App\Exception\TokenException;
use EasySwoole\Http\Request;
use EasySwoole\Jwt\Exception;
use EasySwoole\Jwt\Jwt;

trait OnRequest
{

    /**
     * 请求之前的token验证
     *
     * @param Request $req
     *
     * @return bool
     * @throws Exception
     * @throws TokenException
     */
    protected function verifyToken( Request $req )
    {
        $uid = $this->decodeUid($req);
        $auth_ok = Common::redis()->exists(Constans::REDIS_ADMIN_USER_KEY.$uid);
        if( $auth_ok === 1 )
        {
            Common::setUid($uid);
            return true;
        }
        throw new TokenException();
    }


    /**
     * @param Request $req
     *
     * @param string|null $action
     *
     * @return bool
     * 权限验证
     * @throws RoleException
     * @throws Exception
     */
    protected function authRole( Request $req ,? string $action )
    {
            $no_auth_urlArr = \App\Logic\System::findAll('not_auth_url');
            $requestUri = $this->getVerifyUri($req);
            if( !in_array( $requestUri,$this->noAuthUrl($no_auth_urlArr['v']) ) )
            {
                $uid = $this->decodeUid($req);
                $user_roleArr = Common::redis()->SISMEMBER(Constans::REDIS_ADMIN_ROLE . $uid, $requestUri);
                $role = $user_roleArr === 1;
            }
            else
            {
                $role = true;
            }
            if( !$role )
            {
                throw new RoleException();
            }
            return true;
    }


    /**
     * @param Request $req
     *
     * @return string|null
     * @throws Exception
     */
    private function decodeUid( Request $req) : ? string
    {
        $jwt = Jwt::getInstance()->setSecretKey(Constans::TOKEN_KEY);
        $result = $jwt->decode($req->getHeaderLine('x-token'));
        if(  $result->getStatus() === 1 && $result->getIss() )
        {
            return $result->getIss();
        }
        return null;
    }

    private function noAuthUrl( string $url ) :  array
    {
        if( strpos($url,',') )
        {
            return explode(',',$url);
        }
        return [];
    }

    private function getVerifyUri(Request $req)
    {
        return trim(
            str_replace(
                [URL_SUFFIX,URL_HTML_SUFFIX],
                ['',''],
                $req->getSwooleRequest()->server['request_uri']
            )
            ,'/'
        );
    }

}