<?php
namespace App\Queue;
use EasySwoole\
    {
        Component\Process\AbstractProcess,
        EasySwoole\Queue\Job
    };

    /**
     * 消费者
     */
class Consumer extends AbstractProcess 
{
    protected function run($arg)
    {
       
        go(function () use ( $arg ){
             
            Producer::getInstance()->consumer()->listen(function (Job $job){
                /**
                 * 获取生产者的数据
                 */
                $data = $job->toArray();
                var_dump($data,$arg);
            },5.0);
        });
    }
}

