<?php
namespace Drahak\Restful\Tools\Documentation;

use Nette\DI\Container;
use Nette\Object;

/**
 * Container service spy
 * @package Drahak\Restful\Tools\Documentation
 * @author Drahomír Hanák
 */
class ServiceSpy extends Object
{

	/** @var Container */
	private $container;

	/** @var array */
	private $spies = array();

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Apply spy on given service
	 * @param string $serviceClass
	 * @param string $spyClass
	 * @param array $arguments
	 * @return object  service
	 */
	public function on($serviceClass, $spyClass, array $arguments = array())
	{
		$spy = $this->container->createInstance($spyClass, $arguments);
		$name = $this->container->findByType($serviceClass);
		$name = $name[0];
		$this->container->removeService($name);
		$this->container->addService($name, $spy);

		$this->spies[$serviceClass] = $spy;
		return $spy;
	}

	/**
	 * @param string $serviceClass
	 * @return mixed
	 */
	public final function getSpy($serviceClass)
	{
		return $this->spies[$serviceClass];
	}

	/**
	 * Get all service spies
	 * @return array
	 */
	public final function getSpies()
	{
		return $this->spies;
	}

}