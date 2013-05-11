<?php
namespace Drahak\Restful\Application\Responses;

use Drahak\Restful\Mapping\IMapper;
use Drahak\Restful\Mapping\QueryMapper;
use Nette\Application\IResponse;
use Nette\Object;
use Nette\Http;

/**
 * Text QueryResponse
 * @package Drahak\Restful\Application\Responses
 * @author Drahomír Hanák
 */
class QueryResponse extends Object implements IResponse
{

	/** @var QueryMapper */
	private $mapper;

	/** @var array|\Traversable */
	private $data;

	public function __construct($data)
	{
		$this->mapper = new QueryMapper;
		$this->data = $data;
	}

	/**
	 * Set response mapper
	 * @param IMapper $mapper
	 * @return QueryResponse
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