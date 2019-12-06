<?php
namespace App\HttpController\Admin;
use App\HttpController\BaseController as Controller;
use App\Model\User;

class Index extends Controller
{

    function index()
    {

    //    $data = (new User())->findOne(356);  
    //    return $this->success($data);
    // 还需测试
    // $queue = \App\Queue\Producer::getInstance();
    // $iser = 8;
    // go(function ()use($queue,$iser){
    //     while ($iser < 1){
    //         $job = new \EasySwoole\Queue\Job();
    //         $job->setJobData(time());
    //         $id = $queue->producer()->push($job);
    //         var_dump('job create for Id :'.$id);
    //         --$iser;
    //         sleep(3);
    //     }
    // });
    var_dump('fdfd');
    }

    function login()
    {
        //$request->getParsedBody(); post
        // $request->getQueryParams(); get
        $params = $this->request()->getQueryParams();
        return $this->success($params);
    }
   
}