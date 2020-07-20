<?php

namespace App\Http\Transformers;

class UserAddressTransform
{
    public function transform()
    {
    }

    public function transformUserAddresses($users)
    {
        return response()->json(['user_address' => $users], 200);
    }

    public function transformUserAddress($user, $statusCode = 200)
    {
        return response()->json(['user_address' => $user], $statusCode);
    }
}