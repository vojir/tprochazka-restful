<?php
namespace Drahak\Restful\Application\Responses;

use Drahak\Restful\Mapping\JsonMapper;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

/**
 * JSONP response
 * @package Drahak\Restful\Application\Responses
 * @author Drahomír Hanák
 */
class JsonpResponse extends BaseResponse
{

	/** @var array|\stdClass|\Traversable */
	private $data;

	public function __construct($data, $contentType = NULL)
	{
		parent::__construct($contentType);
		$this->data = $data;
		$this->mapper = new JsonMapper;
	}

	/**
	 * Send JSONP response to output
	 * @param IRequest $httpRequest
	 * @param IResponse $httpResponse
	 */
	public function send(IRequest $httpRequest, IResponse $httpResponse)
	{
		$callback = $httpRequest->getQuery('envelope') ? $httpRequest->getQuery('envelope') : 'callback';
		$httpResponse->setContentType($this->contentType ? $this->contentType : 'application/javascript');
		echo $callback . '(' . $this->mapper->parseResponse($this->data) . ');';
	}


}