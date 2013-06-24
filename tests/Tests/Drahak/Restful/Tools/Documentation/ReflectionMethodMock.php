<?php
namespace Tests\Drahak\Restful\Tools\Documentation;

use Nette\Reflection\AnnotationsParser;
use Nette\Reflection\IAnnotation;
use Nette\Reflection\Method;

/**
 * ReflectionMethodMock
 * @package Tests\Drahak\Restful\Tools\Documentation
 * @author Drahomír Hanák
 */
class ReflectionMethodMock extends Method
{

	public function __construct()
	{
		parent::__construct('Nette\Object', '__get');
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'actionTest';
	}

	/**
	 * @param string $name
	 * @return IAnnotation
	 */
	public function getAnnotation($name)
	{
		return 'GET /resource';
	}


	/**
	 * @return string
	 */
	public function getDescription()
	{
		return 'description';
	}


}