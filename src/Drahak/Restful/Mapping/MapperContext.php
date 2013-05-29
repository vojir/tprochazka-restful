<?php
namespace Drahak\Restful\Mapping;

use Drahak\Restful\InvalidStateException;
use Nette\Object;

/**
 * MapperContext
 * @package Drahak\Restful\Mapping
 * @author Drahomír Hanák
 */
class MapperContext extends Object
{

	/** @var array */
	protected $services = array();

	/**
	 * Add mapper
	 * @param string $contentType
	 * @param IMapper $mapper
	 */
	public function addMapper($contentType, IMapper $mapper)
	{
		$this->services[$contentType] = $mapper;
	}

	/**
	 * Get mapper
	 * @param string $contentType
	 * @return IMapper
	 *
	 * @throws \Drahak\Restful\InvalidStateException
	 */
	public function getMapper($contentType)
	{
		if (!isset($this->services[$contentType])) {
			throw new InvalidStateException('There is no mapper for Content-Type: ' . $contentType);
		}
		return $this->services[$contentType];
	}

}