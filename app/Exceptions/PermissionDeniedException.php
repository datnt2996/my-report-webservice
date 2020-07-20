<?php

namespace App\Exceptions;

use App\Exceptions\ApiException;
use Exception;
use Illuminate\Http\Response;

class PermissionDeniedException
{
    public static function render($message = "Permission denied!"){
        throw new ApiException(
            new Exception($message), 
            'user.error.permission_denied', 
            [],
            Response::HTTP_FORBIDDEN,
            Response::HTTP_FORBIDDEN
        );
    }
	
}