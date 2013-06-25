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
	private $replacedServices = array();

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
		$service = $this->container->getService($name);

		$this->container->removeService($name);
		$this->container->addService($name, $spy);

		$this->replacedServices[$name] = $service;
		return $spy;
	}

	/**
	 * Remove all spies
	 */
	public function removeAll()
	{
		foreach ($this->replacedServices as $name => $service) {
			$this->container->removeService($name);
			$this->container->addService($name, $service);
		}
	}

}