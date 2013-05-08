<?php
namespace Drahak\Restful\Application\Routes;

use Drahak\Restful\IRouteListFactory;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Object;
use Nette\Utils\Finder;

/**
 * RouteListFactoryProxy
 * @package Drahak\Restful\Application\Routes
 * @author Drahomír Hanák
 */
final class RouteListFactoryProxy extends Object implements IRouteListFactory
{

	const CACHE_NAME = 'resourceRouteList';

	/** @var array */
	private $routeConfig;

	/** @var \Nette\Caching\Cache */
	private $cache;

	/**
	 * @param array $routeConfig
	 * @param IStorage $cacheStorage
	 */
	public function __construct(array $routeConfig, IStorage $cacheStorage)
	{
		$this->routeConfig = $routeConfig;
		$this->cache = new Cache($cacheStorage);
	}

	/**
	 * Create resources route list
	 * @param string|null $module
	 * @return ResourceRouteList
	 */
	public function create($module = NULL)
	{
		$routeList = $this->cache->load(self::CACHE_NAME);
		if ($routeList !== NULL) {
			return $routeList;
		}
		return $this->createCached($module);
	}

	/**
	 * Create cached route list
	 * @param null $module
	 * @return ResourceRouteList
	 */
	private function createCached($module = NULL)
	{
		$files = array();
		$presenterFiles = Finder::findFiles('*Presenter.php')->from($this->routeConfig['presentersRoot']);
		foreach ($presenterFiles as $path => $splFile) {
			$files[] = $path;
		}

		$factory = new RouteListFactory($this->routeConfig, $this->cache->storage);
		$routeList = $factory->create($module);
		$this->cache->save(self::CACHE_NAME, $routeList, array(
			Cache::FILES => $files
		));
		return $routeList;
	}

}