<?php
namespace Drahak\Restful\Application\Routes;

use Drahak\Restful\Application\MethodAnnotation;
use Drahak\Restful\IResourceRouter;
use Nette\Application\IRouter;
use Nette\Caching\IStorage;
use Nette\DI\Container;
use Nette\Http\IRequest;
use Nette\Loaders\RobotLoader;
use Nette\Object;

/**
 * RouteListFactory
 * @package Drahak\Restful\Application\Routes
 * @author Drahomír Hanák
 */
class RouteListFactory extends Object
{

    /** @var \Nette\Loaders\RobotLoader */
    private $loader;

    /** @var array */
    private $routeConfig;

    /** @var \Nette\Caching\IStorage */
    private $cacheStorage;

    public function __construct(array $routeConfig, IStorage $cacheStorage)
    {
        $loader = new RobotLoader();
        $loader->addDirectory($routeConfig['presentersRoot']);
        $loader->setCacheStorage($cacheStorage);
        $loader->tryLoad('Drahak\Restful\IResourcePresenter');

        $this->loader = $loader;
        $this->routeConfig = $routeConfig;
        $this->cacheStorage = $cacheStorage;
    }

    /**
     * Create route list
     * @param string|null $module
     * @return ResourceRouteList
     */
    public function create($module = NULL)
    {
        $module = $module ? $module : $this->routeConfig['module'];

        $routeList = new ResourceRouteList($module);
        foreach ($this->loader->getIndexedClasses() as $class => $file) {
            $methods = array(
                IResourceRouter::GET => new MethodAnnotation($class::getReflection(), IRequest::GET),
                IResourceRouter::POST => new MethodAnnotation($class::getReflection(), IRequest::POST),
                IResourceRouter::PUT => new MethodAnnotation($class::getReflection(), IRequest::PUT),
                IResourceRouter::HEAD => new MethodAnnotation($class::getReflection(), IRequest::HEAD),
                IResourceRouter::DELETE => new MethodAnnotation($class::getReflection(), IRequest::DELETE),
            );

            foreach ($methods as $method => $annotations) {
                foreach ($annotations->routes as $destination => $pattern) {

                    $urlPattern = $this->routeConfig['prefix'] ?
                            $this->routeConfig['prefix'] . '/' .  $pattern :
                            $pattern;

                    $routeList[] = new ResourceRoute($urlPattern, $destination, $method);
                }
            }
        }
        return $routeList;
    }

    /**
     * Add routes to router
     * @param IRouter $router
     */
    public function addRoutes(IRouter $router)
    {
        $router[] = $this->create();
    }


}