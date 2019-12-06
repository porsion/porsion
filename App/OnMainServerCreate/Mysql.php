<?php
namespace App\OnMainServerCreate;
use EasySwoole\EasySwoole\Config;
use EasySwoole\ORM\
    { 
        Db\Config as DBconfig,
        DbManager,
        Db\Connection
    };

class Mysql extends AbstractInit
{
        public static function init() : void
        {
            
            // $mysqlConfig =  Config::getInstance()->getConf('MYSQL') ;
            // if( !$mysqlConfig )
            // {
            //     throw new \Exception('mysql config is requred');
            // }
            $dbConfig = new DBconfig();
            $dbConfig->setHost('127.0.0.1');
            $dbConfig->setUser('root');
            $dbConfig->setPassword('adb123456');
            $dbConfig->setDatabase('rpc');
            $dbConfig->setGetObjectTimeout(3.0); //设置获取连接池对象超时时间
            $dbConfig->setIntervalCheckTime(30*1000); //设置检测连接存活执行回收和创建的周期
            $dbConfig->setMaxIdleTime(15); //连接池对象最大闲置时间(秒)
            $dbConfig->setMaxObjectNum(20); //设置最大连接池存在连接对象数量
            $dbConfig->setMinObjectNum(5); //设置最小连接池存在连接对象数量
            DbManager::getInstance()->addConnection(new Connection($dbConfig));
           
        }
}