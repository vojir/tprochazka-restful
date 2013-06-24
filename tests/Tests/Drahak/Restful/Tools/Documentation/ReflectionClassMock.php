<?php
namespace Tests\Drahak\Restful\Tools\Documentation;

use Nette\Reflection\ClassType;
use Nette\Reflection\Method;

/**
 * ReflectionClassMock
 * @package Tests\Drahak\Restful\Tools\Documentation
 * @author Drahomír Hanák
 */
class ReflectionClassMock extends ClassType
{

	public function __construct()
	{
		parent::__construct(get_class());
	}


	/**
	 * @param null|string $filter
	 * @return Method[]
	 */
	public function getMethods($filter = -1)
	{
		return array(new ReflectionMethodMock);
	}

}