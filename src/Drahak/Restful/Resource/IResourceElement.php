<?php
namespace Drahak\Restful\Resource;

/**
 * IResourceElement
 * @package Drahak\Restful\Resource
 * @author Drahomír Hanák
 */
interface IResourceElement
{

	/**
	 * Get element value or array data
	 * @return mixed
	 */
	public function getData();

}
