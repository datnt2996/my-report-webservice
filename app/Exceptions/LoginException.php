<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use App\Exceptions\NotFoundException;

class LoginException
{
	public static function check($model, $email, $password){
       $user = $model->where('email',$email)->where('password',$password)->first();
       if(empty($user)){
           NotFoundException::render();
       }
       return $user;
    }
    
}