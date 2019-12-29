<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/24 下午4:13
 * class:Menu.php
 * Project:YoSoos
 *
 */

namespace App\Logic;

use App\Util\Common;
use EasySwoole\Http\Request;


class Menu
{
        public static function list(Request $req )
        {
            $limit = Common::limit($req);
            $where = [];
            if( $key = $req->getQueryParam('key') )
            {
                $where = ['desc' => ['%'.$key.'%','like'],'k'=> ['%'.$key.'%','like','OR']];
            }

        }

}