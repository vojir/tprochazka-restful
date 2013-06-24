<?php
namespace Drahak\Restful\Tools\Documentation;

use Nette\Object;

/**
 * Resource entity
 * @package Drahak\Restful\Tools
 * @author Drahomír Hanák
 */
final class Resource extends Object
{

	/** @var string */
	public $title;

	/** @var string */
	public $description;

	/** @var string */
	public $path;

	/** @var string */
	public $method;

	/** @var Resource|null */
	public $alias = NULL;

	/** @var array */
	public $request = array(
		'headers' => array(),
		'parameters' => array()
	);

	/** @var array  */
	public $response = array(
		'status' => 200,
		'headers' => array(),
		'data' => array()
	);

	/**
	 * Converts resource entity to array
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'title' => $this->title,
			'description' => $this->description,
			'path' => $this->path,
			'method' => $this->method,
			'alias' => $this->alias ? $this->alias->toArray() : NULL,
			'request' => $this->request,
			'response' => $this->response,
		);
	}

}