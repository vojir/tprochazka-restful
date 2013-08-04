<?php
namespace Drahak\Restful;

use Traversable;

/**
 * IResourceElement
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
interface IResourceElement
{

	/**
	 * Get element value or array data
	 * @return array|Traversable
	 */
	public function getData();

}
