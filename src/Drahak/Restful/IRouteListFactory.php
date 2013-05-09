<?php
namespace Drahak\Restful;

use Drahak\Restful\Application\Routes\ResourceRouteList;
use Nette\Caching\IStorage;

/**
 * IRouteListFactory
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
interface IRouteListFactory
{

	/**
	 * Create resources route list
	 * @param string|null $module
	 * @return ResourceRouteList
	 */
	public function create($module = NULL);

}