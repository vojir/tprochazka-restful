<?php
namespace Drahak\Rest;

/**
 * IResourceFactory
 * @package Drahak\Rest
 * @author Drahomír Hanák
 */
interface IResourceFactory
{

    /**
     * Create new API resource
     * @return IResource
     */
    public function create();

}