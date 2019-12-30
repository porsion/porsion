<?php
namespace App\Util;
use EasySwoole\Component\Context\ContextManager;
use EasySwoole\Component\Context\Exception\ModifyError;
use EasySwoole\Http\Request;
use EasySwoole\Jwt\Jwt;
use EasySwoole\RedisPool\Redis;
class Common 
{
        public static function redis( ) 
        {
            return Redis::defer('redis');
        }

    /**
     * @param $sign
     * @param int|null $time
     *
     * @return false|string
     * 登录成功后，签发token
     */
        public static function createToken( $sign , ? int $time = 3600)
        {
            $obj = Jwt::getInstance()->setSecretKey(Constans::TOKEN_KEY)->publish();
            ### 设置Payload ###
            //设置过期时间 默认为当前时间加2小时
            $obj->setExp(time()+$time);
            //设置签发时间
            $obj->setIat(time());
            //设置签发者
            $obj->setIss($sign);
            return  $obj->__toString();
        }

    /**
     * @param Request $req
     *
     * @return array
     * 解析sql的limit语句
     */
    public static function limit( Request $req )
    {
        $limit = $req->getQueryParams();
        $li = $limit['limit'] ?? 10;
        $pa = $limit['page'] ?? 0;
        $page = $li * ($pa - 1);
        return ['page'=>$page,'limit'=>$li];
    }

    /**
     * @param array $data
     * @param string|null $child
     *
     * @return array
     * 创建树形菜单 主要是针对后台用户组的权限菜单
     */
    static public function create_menu(array $data, ?string $child = 'child' )
    {
            $items = [];
            foreach ($data as $v) {
                $items[$v['auto_id']] = $v;
            }
            $tree = [];
            foreach ($items as $k => $item) {
                $items[$k]['id'] = $k;
                if ($item['pid'] > 0) {
                    $items[$item['pid']][$child][] = &$items[$k];
                } else {
                    $tree[] = &$items[$k];
                }
            }
            return $tree;
    }


    /**
     * @param bool|null $md5
     *
     * @return bool|mixed|string|null
     * 获取UID 全局
     */
    final public static function getUid(? bool $md5 = false)
    {
        $md5_uid = ContextManager::getInstance()->get('admin_uid');
        if( !$md5 )
        {
            return self::redis()->hGet(Constans::REDIS_ADMIN_USER_KEY. $md5_uid,
                'auto_id');
        }
        return $md5_uid;

    }

    /**
     * @param string $uid
     *
     * @throws ModifyError
     * 设置UID 全局
     */
    final public static function setUid( string $uid) : void
    {
        // md5的uid
        ContextManager::getInstance()->set('admin_uid',$uid);
    }

    /**
     * @param null $field
     *
     * @return bool|string
     * 从redis里获取用户的某些资料
     */
    final public static function getUserData( $field = null )
    {
        if( is_null($field) )
        {
            return self::redis()->hGetAll(Constans::REDIS_ADMIN_USER_KEY . self::getUid(true));
        }
        else
        {
            if( is_array($field) )
            {
                $method = 'hMget';
            }
            if( is_string( $field) )
            {
                $method = 'hGet';
            }
            return self::redis()->{$method}(Constans::REDIS_ADMIN_USER_KEY . self::getUid( true ), $field);
        }

    }
}
    