<?php
namespace Drahak\Restful\Application\Responses;

use Drahak\Restful\InvalidArgumentException;
use Drahak\Restful\Mapping\XmlMapper;
use Nette\Http;

/**
 * XmlResponse
 * @package Drahak\Restful\Responses
 * @author Drahomír Hanák
 */
class XmlResponse extends BaseResponse
{

	/**
	 * @param array $data
	 * @param XmlMapper $mapper
	 * @param string|null $contentType
	 */
	public function __construct($data, XmlMapper $mapper, $contentType = NULL)
	{
		parent::__construct($mapper, $contentType);
		if (!$mapper->getRootElement()) {
			$mapper->setRootElement('root');
		}
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
		$httpResponse->setContentType($this->contentType ? $this->contentType : 'application/xml');
		echo $this->mapper->stringify($this->data, $httpRequest->isPrettyPrint());
	}

}
