<?php
namespace Drahak\Api;

use Nette\Application\IResponse;

/**
 * IResponseFactory
 * @package Drahak\Api
 * @author Drahomír Hanák
 */
interface IResponseFactory
{

    /**
     * Create new API response
     * @param IResource $resource
     * @return IResponse
     */
    public function create(IResource $resource);

}