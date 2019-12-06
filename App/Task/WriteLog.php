<?php
namespace App\Task;

use EasySwoole\Task\AbstractInterface\TaskInterface;

class WriteLog implements TaskInterface
{

    protected  $data;


    /**
     * 构造函数需要传参
     */
    public function __construct( ? array $data = [])
    {
        $this->data = $data;
    }

    /**
     * 具体执行的业务
     */
    function run(int $taskId, int $workerIndex)
    {
        var_dump("模板任务运行");
        var_dump("TaskdID:{$taskId}\n WorkerIndex:{$workerIndex}");
        //只有同步调用才能返回数据
       // return "返回值:".$this->data['name'];
        // TODO: Implement run() method.
    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        // TODO: Implement onException() method.
    }


}