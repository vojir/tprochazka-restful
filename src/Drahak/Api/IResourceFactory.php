<?php
namespace Drahak\Api;

/**
 * IResourceFactory
 * @package Drahak\Api
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