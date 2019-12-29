<?php
namespace App\Queue;
use EasySwoole\
    {
        Component\Singleton,
        Queue\Queue
    };

/**
 * 生产者
 */
class Producer extends Queue
{
    use Singleton;

        /**
         * 生产任务数据规则
         * @param array
         * @array = ['data' => '未解析的要操作的数据，用其它进程去解析']
         */
}

        // 生产方式
        // use EasySwoole\Queue\Job;
        // $job = new Job();
        // $job->setJobData(['time'=>\time()]);
        // Producer::getInstance()->producer()->push($job);