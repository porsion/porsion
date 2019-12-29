<?php
namespace App\Util;
use EasySwoole\Component\Context\ContextManager;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Http\Request;
use EasySwoole\Jwt\Jwt;
use EasySwoole\RedisPool\Redis;
class Common 
{
        public static function redis( ) 
        {
            return Redis::defer('redis');
        }
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

    public static function limit( Request $req )
    {
        $limit = $req->getQueryParams();
        $li = $limit['limit'] ?? 10;
        $pa = $limit['page'] ?? 0;
        $page = $li * ($pa - 1);
        return ['page'=>$page,'limit'=>$li];
    }

    static public function create_menu(array $data, ?string $child = 'child' )
    {
        $task = TaskManager::getInstance();
        $data = $task->sync(function()use( $data,$child){
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
        });
        return $data;
    }


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
    