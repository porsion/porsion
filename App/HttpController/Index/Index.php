<?php
namespace App\HttpController\Index;
use App\HttpController\BaseController as Controller,
 App\Model\User;
class Index extends Controller
{

    function index()
    {

        $data = User::create()->findOne(356);
       // var_dump($data);
        // $file = EASYSWOOLE_ROOT.'/vendor/easyswoole/easyswoole/src/Resource/Http/welcome.html';
        // if(!is_file($file)){
        //     $file = EASYSWOOLE_ROOT.'/src/Resource/Http/welcome.html';
        // }
        return $this->success($data);
    }

    function login()
    {
        var_dump($this->request()->getQueryParams());
        return $this->success();
    }
   
}