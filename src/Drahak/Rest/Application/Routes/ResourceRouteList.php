<?php
namespace Drahak\Rest\Application\Routes;

use Drahak\Rest\IResourceRouter;
use Drahak\Rest\InvalidStateException;
use Nette\Application\Routers\RouteList;

/**
 * ResourceRouteList
 * @package Drahak\Rest\Route
 * @author Drahomír Hanák
 */
class ResourceRouteList extends RouteList
{

    /**
     * Set offset
     * @param mixed $index
     * @param mixed $route
     * @throws \Drahak\Rest\InvalidStateException
     */
    public function offsetSet($index, $route)
    {
        if (!($route instanceof IResourceRouter)) {
            throw new InvalidStateException('ResourceRouteList expects IResourceRoute');
        }
        parent::offsetSet($index, $route);
    }

}