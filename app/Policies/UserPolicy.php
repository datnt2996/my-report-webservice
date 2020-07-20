<?php

namespace App\Policies;

use App\Exceptions\NotFoundException;
use App\Helpers\AuthHelper;

class UserPolicy
{
	public static function checkUser($userId)
	{
		if ($userId != AuthHelper::getUserID()) {
			NotFoundException::render();
		}

		return $userId;
	}
}