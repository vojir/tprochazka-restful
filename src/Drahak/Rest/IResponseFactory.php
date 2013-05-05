<?php
namespace Drahak\Rest;

use Nette\Application\IResponse;

/**
 * IResponseFactory
 * @package Drahak\Rest
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