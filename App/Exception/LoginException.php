<?php
namespace App\Exception;
use throwable;
use App\Util\HttpStatusCode;
class LoginException extends \Exception
{
    function __construct(Throwable $previous = null)
    {
        
        parent::__construct("the request is requrie login", HttpStatusCode::NO_LOGIN, $previous);
    }

}