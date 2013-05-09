<?php
namespace Drahak\Restful\Application\Routes;

use Drahak\Restful\Application\RouteAnnotation;
use Drahak\Restful\IResourceRouter;
use Drahak\Restful\IRouteListFactory;
use Drahak\Restful\InvalidStateException;
use Nette\Caching\IStorage;
use Nette\DI\Container;
use Nette\Http\IRequest;
use Nette\Loaders\RobotLoader;
use Nette\Object;
use Nette\Reflection\Method;

/**
 * RouteListFactory
 * @package Drahak\Restful\Application\Routes
 * @author Drahomír Hanák
 */
class RouteListFactory extends Object implements IRouteListFactory
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
			$classReflection = $class::getReflection();
			$methods = array(
				IResourceRouter::GET => new RouteAnnotation($classReflection, IRequest::GET),
				IResourceRouter::POST => new RouteAnnotation($classReflection, IRequest::POST),
				IResourceRouter::PUT => new RouteAnnotation($classReflection, IRequest::PUT),
				IResourceRouter::HEAD => new RouteAnnotation($classReflection, IRequest::HEAD),
				IResourceRouter::DELETE => new RouteAnnotation($classReflection, IRequest::DELETE),
			);

			// Fetch routes data
			$routeData = array();
			foreach ($methods as $method => $annotations) {
				/** @var Method $methodReflection  */
				foreach ($annotations->routes as $destination => $methodReflection) {

					$pattern = $methodReflection->getAnnotation($method);
					$urlPattern = $this->routeConfig['prefix'] ?
							$this->routeConfig['prefix'] . '/' .  $pattern :
							$pattern;

					$splited = explode(':', $destination);
					$action = array_pop($splited);

					$routeData[$urlPattern][$method] = $action;
				}
			}

			// Create joined Resource routes form routes data
			foreach ($routeData as $mask => $dictionary) {
				$routeList[] = new ResourceRoute($mask, array(
					'presenter' => str_replace('Presenter', '', $classReflection->getShortName()),
					'action' => $dictionary
				), IResourceRouter::RESTFUL);
			}
		}
		return $routeList;
	}

}