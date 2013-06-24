<?php
namespace Tests\Drahak\Restful\Tools\Documentation;

/**
 * ClassFake
 * @package Tests\Drahak\Restful\Tools\Documentation
 * @author Drahomír Hanák
 */
class ClassFake
{

	/**
	 * @return ReflectionClassMock
	 */
	public static function getReflection()
	{
		return new ReflectionClassMock;
	}

}