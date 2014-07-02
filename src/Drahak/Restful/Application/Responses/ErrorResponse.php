<?php

namespace Drahak\Restful\Application\Responses;

use Nette\Application\IResponse;
use Nette\Http;
use Nette\Object;

class ErrorResponse extends Object implements IResponse {

	private $response;

	private $code;

	/**
	 * @param IResponse $response Wrapped response with data
	 * @param int $errorCode
	 */
	public function __construct($response, $code = 500)
	{
		$this->response = $response;
		$this->code = $code;
	}

	/**
	 * Sends response to output
	 * @param Http\IRequest $httpRequest
	 * @param Http\IResponse $httpResponse
	 */
	public function send(Http\IRequest $httpRequest, Http\IResponse $httpResponse) {
		$httpResponse->setCode($this->code);
		$this->response->send($httpRequest, $httpResponse);
	}

}
 