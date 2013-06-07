<?php
namespace Drahak\Restful\Resource;

use Drahak\Restful\IDataResource;
use Nette\Object;

/**
 * Resource data decorator
 * @package Drahak\Restful\Resource
 * @author Drahomír Hanák
 */
abstract class Decorator extends Object implements IDataResource
{

	/** @var IDataResource */
	private $resource;

	/**
	 * @param IDataResource $resource
	 */
	public function __construct(IDataResource $resource)
	{
		$this->resource = $resource;
	}

	/**
	 * Return data
	 * @return array|\stdClass|\Traversable
	 */
	public function getData()
	{
		return $this->resource->getData();
	}

}