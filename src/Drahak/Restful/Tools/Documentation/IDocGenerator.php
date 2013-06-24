<?php
namespace Drahak\Restful\Tools\Documentation;

/**
 * IDocGenerator
 * @package Drahak\Restful\Tools\Documentation
 * @author Drahomír Hanák
 */
interface IDocGenerator
{

	/**
	 * Generate documentation
	 * @return array
	 */
	public function generate();

}