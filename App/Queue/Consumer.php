<?php
namespace App\Queue;
use EasySwoole\
    {
        Component\Process\AbstractProcess,
        Queue\Job
    };

    /**
     * 消费者
     */
class Consumer extends AbstractProcess 
{
    const PRODUCER = 'system_log';
    protected function run($arg)
    {
       
        go(function (){
             
            Producer::getInstance()->consumer()->listen(function (Job $job){
                /**
                 * 获取生产者的数据
                 */
                $data = $job->getJobData();
                go(function() use ($data) {
                    self::goJob($data);
                });
            },5.0);
        });
    }


    /**
     * @param array $data
     * 提交过来的data['func']必须是一个类class@method的形式
     */
    private static function goJob( array $data )
    {
        /*if( !$data['func'] || !$data['data']) return ;
        if( strpos($data['func'],'@') < 1)
        {
            return ;
        }
        $semis = \explode('@',$data['func']);
        if( class_exists($semis[0]) )
        {
            if( \method_exists($semis[0],'getInstance') )
            {
                $semis[0]::getInstance()->{$semis[1]}($data['data']);
            }
            else if( \method_exists($semis[0],$semis[1]) )
            {
                $semis[0]::$semis[1]($data['data']);
            }
        }*/
    }
}

