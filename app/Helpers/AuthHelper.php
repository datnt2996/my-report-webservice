<?php

namespace App\Helpers;

use Exception;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AuthHelper
{
	public static function hasRole($role)
	{
		$decoded = self::decodeJwtToken();

		if (empty($decoded->sub)) {
			return null;
		}

		if (!str_contains($decoded->scope, $role)) {
			return false;
		}

		return true;
	}

	public static function getToken()
	{
		$bearerToken = request()->bearerToken();
		if (empty($bearerToken)) {
			$bearerToken = request()->get('auth_token');
		}

		return $bearerToken;
	}

	public static function getUserID()
	{
		$decoded = self::decodeJwtToken();

		if (empty($decoded->sub)) {
			return new Exception('Jwt sub is empty');
		}

		return $decoded->sub;
	}

	public static function getShopID()
	{
		$decoded = self::decodeJwtToken();

		if (empty($decoded->sub)) {
			throw new Exception('Jwt sub is empty');
		}

		if (!str_contains($decoded->scope, 'shop')) {
			throw new Exception('This user not a shop');
		}

		return $decoded->sub;
	}

	public static function isOrganization()
	{
		$decoded = self::decodeJwtToken();

		if (empty($decoded->sub)) {
			return null;
		}

		if (!str_contains($decoded->scope, 'organization')) {
			return false;
		}

		return true;
	}

	public static function isAdmin()
	{
		$decoded = self::decodeJwtToken();
		if (empty($decoded->sub)) {
			return null;
		}
		

		if (!Str::contains($decoded->scope, 'admin')) {
			return false;
		}

		return true;
	}

	public static function decodeJwtToken()
	{
		$bearerToken = self::getToken();

		if (empty($bearerToken)) {
			throw new Exception('Jwt token is empty');
		}

		try {
			JWT::$leeway = 60;

			return JWT::decode($bearerToken, config('app.jwt_secret'), ['HS256']);
		} catch (\Firebase\JWT\ExpiredException $e) {
			Log::error($e->getMessage());

			return $e;
		} catch (\Exception $e) {
			Log::error($e->getMessage());

			return $e;
		}
	}

	public static function generateUserToken($userID, $isOrganization, $isAdmin = false)
	{
		if (empty($userID)) {
			throw new Exception('userID is empty');
		}

		$cacheKey = 'gen:token:'.($userID.':'.$isOrganization ? 'organization' : 'user').':'.($isAdmin ? 'admin' : '');
		$jwt = Cache::get($cacheKey);

		if (empty($jwt)) {
			$issuedAt = time();
			$expirationTime = $issuedAt + 21600;
			$scope = '/user';

			if ($isOrganization) {
				$scope .= '/organization';
			}

			if ($isAdmin) {
				$scope .= '/admin';
			}

			$payload = [
				'iss' => 'MY_REPORT_AUTH',
				'iat' => $issuedAt,
				'exp' => $expirationTime,
				'sub' => $userID,
				'scope' => $scope,
				'sign' => 'system',
				'cre' => time(),
			];

			$key = env('JWT_SECRET');
			if (empty($key)) {
				throw new Exception('JWT_SECRET is empty');
			}

			$alg = 'HS256';
			$jwt = JWT::encode($payload, $key, $alg);

			Cache::put($cacheKey, $jwt, 30);
		}

		return $jwt;
	}
}