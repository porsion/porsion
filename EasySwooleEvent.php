<?php
namespace EasySwoole\EasySwoole;


use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\EasySwoole\Config;
use EasySwoole\ORM\Db\Config as DBconfig;
use EasySwoole\ORM\Exception\Exception as MysqlException;
use EasySwoole\EasySwoole\Command\Utility;
class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {

        // TODO: Implement mainServerCreate() method.
        try{
            $mysqlConfig =  Config::getInstance()->getConf('MYSQL') ;
            if( !$mysqlConfig )
            {
                throw new \RuntimeException('mysql config is requre');
            }
            $dbConfig = new DBconfig();
            foreach( array_filter($mysqlConfig) as $k => $v )
            {
                $setItem = 'set'.ucfirst(trim($k));
                $dbConfig->{$setItem}($v);
            }
            DbManager::getInstance()->addConnection(new Connection($dbConfig));
            
        }
        catch( \RuntimeException $e )
        {
               exit(Utility::displayItem($e->getMessage(), null)."\n");
        }
        catch( MysqlException $e )
        {
            \print_r( $e->getMessage() );
        }
       
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}