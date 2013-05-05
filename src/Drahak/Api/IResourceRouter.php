<?php
namespace Drahak\Api;

use Nette\Application\IRouter;
use Nette\Http\IRequest;

/**
 * IResourceRouter
 * @package Drahak\Api\Routes
 * @author Drahomír Hanák
 */
interface IResourceRouter extends IRouter
{

    /** Resource types */
    const GET = IRequest::GET;
    const POST = IRequest::POST;
    const PUT = IRequest::PUT;
    const HEAD = IRequest::HEAD;
    const DELETE = IRequest::DELETE;

    /**
     * Get cuurrent route method
     * @return string
     */
    public function getMethod();

}