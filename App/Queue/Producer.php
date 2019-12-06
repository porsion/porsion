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
}

        // 生产方式
        // use EasySwoole\Queue\Job;
        // $job = new Job();
        // $job->setJobData(['time'=>\time()]);
        // Producer::getInstance()->producer()->push($job);