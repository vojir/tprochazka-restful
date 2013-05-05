<?php
namespace Drahak\Rest;

use Nette\Object;

/**
 * ResourceFactory
 * @package Drahak\Rest
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