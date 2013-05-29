<?php
namespace Drahak\Restful\Application\Responses;

use Drahak\Restful\Mapping\DataUrlMapper;
use Drahak\Restful\Mapping\IMapper;
use Nette\Application\IResponse;
use Nette\Object;
use Nette\Http;

/**
 * DataUrlResponse
 * @package Drahak\Restful\Application\Responses
 * @author Drahomír Hanák
 */
class DataUrlResponse extends Object implements IResponse
{

	/** @var IMapper */
	private $mapper;

	/** @var array|\stdClass|\Traversable */
	private $data;

	/**
	 * @param array $data
	 */
	public function __construct($data)
	{
		$this->data = $data;
		$this->mapper = new DataUrlMapper;
	}

	/**
	 * Change XmlResponse mapper
	 * @param IMapper $mapper
	 * @return XmlResponse
	 */
	public function setMapper(IMapper $mapper)
	{
		$this->mapper = $mapper;
		return $this;
	}

	/**
	 * Sends response to output
	 * @param Http\IRequest $httpRequest
	 * @param Http\IResponse $httpResponse
	 */
	public function send(Http\IRequest $httpRequest, Http\IResponse $httpResponse)
	{
		$httpResponse->setContentType('text/plain');
		echo $this->mapper->parseResponse($this->data);
	}


}