<?php

namespace App\Http\Transformers;

class UserTransform
{
	public function transform()
	{
	}

	public function transformUsers($users)
	{
		return response()->json(['users' => $users], 200);
	}

	public function transformUser($user, $statusCode = 200)
	{
		return response()->json(['user' => $user], $statusCode);
	}
}