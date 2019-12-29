<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/23 下午6:17
 * class:System.php
 * Project:YoSoos
 *
 */

namespace App\Logic;
use App\Model\Config;
use App\Util\Common;
use App\Util\Constans;
use EasySwoole\ORM\Exception\Exception;

class System
{
    public static function findAll( $field = null ,? bool $type = false)
    {
        $data = Common::redis()->hGetAll(Constans::REDIS_SYSTEM_CONFIG);
        if($data)
        {
            if( !is_null($field) ) {

                $temfield = (array)$field;
                $data = Common::redis()->hMget(Constans::REDIS_SYSTEM_CONFIG, $temfield);
                if( is_string($field) )
                    $data = $data[0];
            }
        }
       else
        {
            $arr = [];
            $data = Config::create()->findAll();
            foreach($data as $k => $v)
            {
                $arr[$v['k']] = $v;
            }
            Common::redis()->hMset(Constans::REDIS_SYSTEM_CONFIG,$arr);
            if( $field )
            {
                $data = [];
                if( is_array($field) )
                {
                    foreach($field as $item)
                    {
                        $data[] = $arr[$item];
                    }
                }
                else if( is_string($field) )
                {
                    $data = $arr[$field];
                }
            }
            else
            {
                $data = $arr;
            }
        }
       if($type)
       {
           $data = self::arr_map($data);
       }
        return $data;
    }

    public static function model() : Config
    {
        return Config::create();
    }


    private static function arr_map(array $arr)
    {
        $data = [];
        foreach($arr as $k =>$v)
        {
            if($v['type'] == 'admin')
            {
                $data['admin'][] = $v;
            }
            else
            {
                    $data['admin'][] = $v;
            }
        }
        return $data;
    }

    public static function saveSigle(?array $data) : void
    {
        go(function() use ($data) {
            Config::create()->connection('write')->where(['k'=>$data['k']])
                ->update($data);
            self::clearRedisConfig();
            self::findAll();
        });
    }


    /**
     * @param array $data
     *
     * @return bool 新增或保存修改
     * 新增或保存修改
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws Exception
     * @throws \Throwable
     */
    public static function update( array $data) : bool
    {
            $ret = 0;
            $insert_mode = Config::create()->connection('write',true);
            if(isset($data['auto_id']) && $data['auto_id'] > 0)
            {
                    $old_data = Config::create()->get($data['auto_id'],true);
                    if( $old_data )
                    {
                        $temp_data = array_merge($old_data,$data);
                        $ret = $insert_mode->update($temp_data);
                    }
            }
            else
            {
                $ret = $insert_mode->data($data,false)->save();
            }
            if( (int) $ret > 0 )
            {
                self::clearRedisConfig();
            }
            return $ret > 0;
    }


    /**
     * 清空redis里的系统配置缓存
     */
    public static function clearRedisConfig() :  void
    {
        Common::redis()->del(Constans::REDIS_SYSTEM_CONFIG);
    }


}