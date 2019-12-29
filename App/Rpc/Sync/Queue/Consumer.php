<?php

/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/19 下午3:50
 * class:Consumer.php
 * Project:YoSoos
 *
 */

namespace App\Rpc\Sync\Queue;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\Queue\Job;

/**
 * 消费者
 */
class Consumer extends AbstractProcess
{
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
            });
        });
    }

    /**
     * @param array $data
     * 提交过来的data['func']必须是一个类class@method的形式
     */
    private static function goJob( array $data ) : void
    {
        if( !$data['func'] || !$data['data'] ) return ;
        if( strpos($data['func'],'@') < 1)
        {
            return ;
        }
        $semis = \explode('@',$data['func']);
        if( \method_exists( $semis[0],'getInstance') )
        {
            $semis[0]::getInstance()->{$semis[1]}($data['data']);
        }
        else if( \method_exists($semis[0],$semis[1]) )
        {
            $semis[0]::$semis[1]($data['data']);
        }

    }
}

