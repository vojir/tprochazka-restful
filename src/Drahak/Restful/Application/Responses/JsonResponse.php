<?php
namespace Drahak\Restful\Application\Responses;

use Drahak\Restful\Mapping\IMapper;
use Nette\Http;
use Drahak\Restful\Mapping\JsonMapper;
use Drahak\Restful\InvalidArgumentException;

/**
 * JsonResponse with pretty print support
 * @package Drahak\Restful\Application\Responses
 * @author Drahomír Hanák
 */
class JsonResponse extends BaseResponse
{

	/**
	 * @param array $data
	 * @param IMapper $mapper
	 * @param string|null $contentType
	 */
	public function __construct($data, IMapper $mapper, $contentType = NULL)
	{
		parent::__construct($mapper, $contentType);
		$this->mapper = new JsonMapper();
		$this->data = $data;
	}

	/**
	 * Sends response to output
	 * @param Http\IRequest $httpRequest
	 * @param Http\IResponse $httpResponse
	 * @throws InvalidArgumentException
	 */
	public function send(Http\IRequest $httpRequest, Http\IResponse $httpResponse)
	{
		$this->checkRequest($httpRequest);
		$httpResponse->setContentType($this->contentType ? $this->contentType : 'application/json');
		echo $this->mapper->stringify($this->data, $httpRequest->isPrettyPrint());
	}

}
