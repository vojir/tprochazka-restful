<?php
namespace Drahak\Restful;

use Reflector;

/**
 * IAnnotationParser
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
interface IAnnotationParser
{

	/**
	 * Parse annotation for given class, method or any reflection
	 * @param Reflector $reflection
	 * @return mixed|void
	 */
	public function parse(Reflector $reflection);

}