<?php
namespace Drahak\Restful\Tools\Documentation;

/**
 * IRequestRunner
 * @package Drahak\Restful\Tools\Documentation
 * @author Drahomír Hanák
 */
interface IRequestRunner
{

	/**
	 * Run request
	 * @param string $requestString e.g. GET /resource
	 * @param array $data
	 * @return array|\stdClass
	 */
	public function run($requestString, $data = array());

}