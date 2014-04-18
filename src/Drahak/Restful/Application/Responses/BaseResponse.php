<?php
namespace Drahak\Restful\Application\Responses;

use Drahak;
use Drahak\Restful\InvalidArgumentException;
use Drahak\Restful\Mapping\IMapper;
use Nette\Application\IResponse;
use Nette\Http\IRequest;
use Nette\Object;

/**
 * BaseResponse
 * @package Drahak\Restful\Application\Responses
 * @author Drahomír Hanák
 *
 *  @property-read string $contentType
 *  @property-write IMapper $mapper
 */
abstract class BaseResponse extends Object implements IResponse
{

	/** @var array|\stdClass|\Traversable */
	protected $data;

	/** @var IMapper */
	protected $mapper;

	/** @var string */
	protected $contentType;

	/**
	 * @param null $contentType
	 * @param IMapper $mapper
	 */
	public function __construct(IMapper $mapper, $contentType = NULL)
	{
		$this->contentType = $contentType;
		$this->mapper = $mapper;
	}

	/**
	 * Is pretty print enabled
	 * @param  IRequest $request 
	 * @return boolean           
	 */
	protected function isPrettyPrint(IRequest $request)
	{
		$prettyPrint = $request->getQuery('prettyPrint');
		if ($prettyPrint === 'false') {
			return FALSE;
		}
		return $prettyPrint === NULL ? TRUE : (bool)$prettyPrint;
	}

	/**
	 * Get response content type
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * Get response data
	 * @return array|\stdClass|\Traversable
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Set mapper
	 * @param IMapper $mapper
	 * @return BaseResponse
	 */
	public function setMapper(IMapper $mapper)
	{
		$this->mapper = $mapper;
		return $this;
	}

}
