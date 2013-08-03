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
	 * Get resource element name
	 * @return string
	 */
	public function getName();

	/**
	 * Get element value or array data
	 * @return mixed
	 */
	public function getData();

}
