<?php
namespace App\Model;
use EasySwoole\ORM\AbstractModel;

/**
 * 后台用户操作类
 */
class User extends AbstractModel
{
    /**
     * 要操作的表名
     */
    protected $tableName = 'user';

    /**
     * 是否自动写入时间戳
     * @var bool
     */
    protected $autoTimeStamp = true;

    /**
     * 自动写入时间戳的字段
     * @var string
     */
    protected $createTime = 'create_time';


    /**
     * 自动更新时间的字段名
     * 如果值为false将不自动更新
     * @var string|bool
     */
    protected $updateTime = 'update_time';



}