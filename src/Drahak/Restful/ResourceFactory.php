<?php
namespace Drahak\Restful;

use Nette\Object;

/**
 * ResourceFactory
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
class ResourceFactory extends Object implements IResourceFactory
{

	/**
	 * Create new API resource
	 * @return IResource
	 */
	public function create()
	{
		return new Resource();
	}

}