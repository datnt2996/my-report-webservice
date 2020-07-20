<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Log;

/**
 * HTTP Status Code follow: https://help.shopify.com/en/api/getting-started/response-status-codes.
 */
class ApiException extends Exception
{
	protected $httpCode;
	protected $errorCode;
	protected $errors = [];
	protected $messageCode;
	protected $message;
	protected $exception;

	public function __construct(
		$exception,
		$messageCode,
		array $errors,
		$errorCode,
		$httpCode = Response::HTTP_INTERNAL_SERVER_ERROR
	) {
		$this->exception = $exception;
		$this->httpCode = $httpCode;
		$this->errorCode = $errorCode;
		$this->errors = $errors;
		$this->messageCode = $messageCode;
		$this->message = __($messageCode);

		parent::__construct($this->message, $httpCode);
	}

	public function render($request)
	{
		$json = [
			'code' => $this->httpCode,
			'error_code' => $this->errorCode,
			'message_code' => $this->messageCode,
			'message' => $this->message,
			'exception' => $this->exception->getMessage()
		];

		return new JsonResponse($json, $this->httpCode);
	}

	public function report()
	{
		Log::emergency('['.$this->errorCode.' - HTTP '.$this->httpCode.' - Msg Code '
		.$this->messageCode.'] '.$this->message.'- '.json_encode($this->errors));
	}
}