<?php
namespace Drahak\Api\Application\Routes;

use Drahak\Api\IResourceRouter;
use Drahak\Api\InvalidStateException;
use Nette\Application\Routers\RouteList;

/**
 * ResourceRouteList
 * @package Drahak\Api\Route
 * @author Drahomír Hanák
 */
class ResourceRouteList extends RouteList
{

    /**
     * Set offset
     * @param mixed $index
     * @param mixed $route
     * @throws \Drahak\Api\InvalidStateException
     */
    public function offsetSet($index, $route)
    {
        if (!($route instanceof IResourceRouter)) {
            throw new InvalidStateException('ResourceRouteList expects IResourceRoute');
        }
        parent::offsetSet($index, $route);
    }

}