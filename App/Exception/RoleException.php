<?php
/**
 *
 * Author:YoSoos
 * web:https://www.yosoos.com
 * date:2019/12/24 下午1:25
 * class:RoleException.php
 * Project:YoSoos
 *
 */

namespace App\Exception;


use App\Util\HttpStatusCode;

class RoleException extends \Exception
{
    function __construct(\Throwable $previous = null)
    {

        parent::__construct("you have \'n role!", HttpStatusCode::NO_AUTH, $previous);
    }
}