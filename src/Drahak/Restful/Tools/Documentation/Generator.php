<?php
namespace Drahak\Restful\Tools\Documentation;

use Drahak\Restful\Utils\Strings;
use Nette\Application\Application;
use Nette\Application\UI\PresenterComponentReflection;
use Nette\Caching\IStorage;
use Nette\DI\Container;
use Nette\Loaders\RobotLoader;
use Nette\Object;
use Nette\Reflection\ClassType;

/**
 * Documentation generator
 * @package Drahak\Restful\Tools
 * @author Drahomír Hanák
 *
 * @property-write RobotLoader $presenterLoader
 */
class Generator extends Object implements IDocGenerator
{

	/** @var string */
	private $sourceDirectory;

	/** @var ResourceFactory */
	private $resourceFactory;

	/** @var RobotLoader */
	private $presenterLoader;

	public function __construct($presentersDir, ResourceFactory $resourceFactory, IStorage $cacheStorage)
	{
		$loader = new RobotLoader();
		$loader->addDirectory($presentersDir);
		$loader->setCacheStorage($cacheStorage);

		$this->sourceDirectory = $presentersDir;
		$this->resourceFactory = $resourceFactory;
		$this->presenterLoader = $loader;
	}

	/**
	 * Set presenter class loader
	 * @param RobotLoader $loader
	 * @return Generator
	 */
	public function setPresenterLoader(RobotLoader $loader)
	{
		$this->presenterLoader = $loader;
		return $this;
	}

	/**
	 * Generate documentation from example requests
	 * @return array
	 */
	public function generate()
	{
		$resources = array();

		$this->presenterLoader->tryLoad('Drahak\Restful\Application\IResourcePresenter');
		foreach ($this->presenterLoader->getIndexedClasses() as $class => $file) {
			/** @var ClassType $classReflection */
			$classReflection = $class::getReflection();
			$annotations = $classReflection->getAnnotations();
			$title = isset($annotations['description']) ? $annotations['description'][0] : NULL;

			$data = array();
			$data['title'] = $title;
			$data['description'] = $classReflection->getDescription();
			$data['resources'] = array();

			foreach ($classReflection->getMethods() as $method) {
				if (Strings::substring($method->getName(), 0, 6) !== 'action') {
					continue;
				}

				try {
					$data['resources'][] = $this->resourceFactory->createResourceDoc($method);
				} catch (InvalidExampleRequestException $e) { /** so don't generate doc */ }
			}
			if (count($data['resources'])) $resources[] = $data;
		}
		return $resources;
	}

}