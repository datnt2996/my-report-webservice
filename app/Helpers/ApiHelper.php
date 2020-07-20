<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class ApiHelper
{
	/*
	 * @param string $serviceCode ex: user-v1, product-v1, user-v2
	 * @param string                 $method
	 * @param string|null              $uri
	 * @param array              $options
	 * @param string|null              $jwtToken
	 *
	 * @return mixed
	 */
	public static function request(
		$serviceCode,
		$method,
		$uri,
		array $options = null,
		$jwtToken = null,
		$httpErrors = false
	) {
		$isDebugEnabled = env('APP_DEBUG');

		if ($isDebugEnabled) {
			$executionStartTime = microtime(true);
		}

		$client = new Client([
			'base_uri' => $serviceCode,
		]);

		$options['http_errors'] = $httpErrors;

		if (!empty($jwtToken)) {
			if (!str_contains($jwtToken, ' ')) {
				$jwtToken = 'Bearer '.$jwtToken;
			}

			$options['headers']['Authorization'] = $jwtToken;
		}

		$uri = ltrim($uri, '/');
		$request = $client->request($method, $uri, $options);

		if ($isDebugEnabled) {
			$executionEndTime = microtime(true);
			$seconds = $executionEndTime - $executionStartTime;
			$url = $serviceBaseUri.$uri;
			\Log::debug('[API DEBUG] Call '.$url.' take '.$seconds.' - response http status code: '
			.$request->getStatusCode().' - options: '.json_encode($options));
		}

		return $request;
	}

	public static function isAdmin()
	{
		$isAdmin = request()->header('is-admin', false);
		if (!$isAdmin) {
			$isAdmin = request()->input('is_admin', false);
		}

		return $isAdmin === 'true' || $isAdmin === '1';
	}

	public static function responseError($httpStatusCode, $errorCode, $messageCode, $message)
	{
		return response()->json([
			'code' => $httpStatusCode,
			'error_code' => $errorCode,
			'message_code' => $messageCode,
			'message' => $message
		], $httpStatusCode);
	}
}