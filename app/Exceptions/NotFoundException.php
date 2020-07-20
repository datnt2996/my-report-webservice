<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NotFoundException
{

	public static function render(
        $message = 'not found user', 
        $messageCode = 'user.errors.not_found'
    ){
        throw new ApiException(
            new Exception($message),
            $messageCode,
            [],
            404,
            Response::HTTP_NOT_FOUND
        );
    }
}