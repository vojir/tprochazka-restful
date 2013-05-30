<?php
namespace Drahak\Restful\Application\Responses;

use Drahak\Restful\Mapping\IMapper;
use Nette\Application\IResponse;
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

	/** @var IMapper */
	protected $mapper;

	/** @var string */
	protected $contentType;

	/**
	 * @param string|NULL $contentType
	 */
	public function __construct($contentType = NULL)
	{
		$this->contentType = $contentType;
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