<?php
namespace App\Exception;
use throwable;
use App\Util\HttpStatusCode;
class TokenException extends \Exception
{
    function __construct(Throwable $previous = null)
    {
        
        parent::__construct("The Token is dany", HttpStatusCode::NO_TOKEN, $previous);
    }

}