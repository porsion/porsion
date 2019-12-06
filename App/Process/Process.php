<?php
namespace App\Process;
use EasySwoole\Component\Process\AbstractProcess;
use Swoole\Coroutine;

/**
 * 自定义进程
 */
class Process extends AbstractProcess
{
 
    private $data = [];
    protected function run($arg)
    {
    //    $size = $arg['size'];
    //    $pid = $this->getPid();
    //    go(function() use ($size,$pid)
    //    {
    //        while(true)
    //        {
    //             $this->data[] = 'sersdrs';
    //             if( count($this->data) > $size )
    //             {
                    
    //                 echo 'warn'.count($this->data).PHP_EOL."My Pid is {$pid}".PHP_EOL;
    //             }
        
    //             Coroutine::sleep(1);
    //       }
    //     });
    }
    
    protected function onPipeReadable(\Swoole\Process $process)
    {
        /*
         * 该回调可选
         * 当有主进程对子进程发送消息的时候，会触发的回调，触发后，务必使用
         * $process->read()来读取消息
         * 这里可以接收主进程发送的信号
         */

    //     $command = $process->read();
     //    var_dump($command);
        //  if( $command == 'clear' )
        //  {
        //      $this->data = [];
        //  }
        //  else
        //  {
        //     echo 'request warn' . PHP_EOL;
           
        //  }
    }
    
    protected function onShutDown()
    {
        /*
         * 该回调可选
         * 当该进程退出的时候，会执行该回调
         */
    }
    
    protected function onException(\Throwable $throwable, ...$args)
    {
        /*
         * 该回调可选
         * 当该进程出现异常的时候，会执行该回调
         */
    }
}