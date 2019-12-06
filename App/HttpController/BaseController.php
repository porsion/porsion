<?php
namespace App\HttpController;
use EasySwoole\Http\AbstractInterface\Controller;


class BaseController extends Controller
{
        
        use \App\Util\OnRequest,  \App\Util\HttpStatus;
        
        public function index()
        {

        } 

       
}