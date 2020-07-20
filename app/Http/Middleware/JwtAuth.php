<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;

class JwtAuth
{
	public function handle($request, Closure $next)
	{
		$bearerToken = $request->bearerToken();
		if (empty($bearerToken)) {
			//get token from query string
			$bearerToken = $request->get('auth_token');
		}

		if (empty($bearerToken)) {
			return response()->json([
				'errors' => '[API] API key or access token is empty',
			], 401);
		}

		try {
			JWT::$leeway = 60;
			JWT::decode($bearerToken, config('app.jwt_secret'), ['HS256']);
		} catch (\Firebase\JWT\ExpiredException $e) {
			Log::error('[JwtAuth Expired] '.$e->getMessage().' - token: '.$bearerToken);

			return response()->json([
				'errors' => '[JWT Auth Error] '.$e->getMessage(),
			], 401);
		} catch (\Exception $e) {
			Log::error('[JwtAuth] '.$e->getMessage().' - token: '.$bearerToken);

			return response()->json([
				'errors' => '[JWT Auth Error] '.$e->getMessage(),
			], 401);
		}

		return $next($request);
	}
}