<?php
namespace Drahak\Restful;

use Drahak\Restful\Resource\CamelCaseDecorator;
use Drahak\Restful\Resource\SnakeCaseDecorator;
use Nette\Object;

/**
 * ResourceFactory
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
class ResourceFactory extends Object implements IResourceFactory
{

	/** Snake case key format */
	const SNAKE_CASE = 'snake_case';
	/** Camel case key format */
	const CAMEL_CASE = 'camelCase';

	/** @var string */
	private $convention = self::CAMEL_CASE;

	public function __construct($convention)
	{
		$this->convention = $convention;
	}

	/**
	 * Create new API resource
	 * @return IResource
	 */
	public function create()
	{
		$resource = new Resource;
		if ($this->convention === self::SNAKE_CASE) {
			$resource = new SnakeCaseDecorator($resource);
		} else if ($this->convention === self::CAMEL_CASE) {
			$resource = new CamelCaseDecorator($resource);
		}
		return $resource;
	}

}