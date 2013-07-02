<?php
namespace Drahak\Restful\Resource;

use Drahak\Restful\IResource;
use Traversable;
use DateTime;

/**
 * DateTimeDecorator
 * @package Drahak\Restful\Resource
 * @author Drahomír Hanák
 */
class DateTimeDecorator extends Decorator
{

	/** DateTime format */
	private $format = 'c';

	/**
	 * @param IResource $resource
	 * @param string $format of date time
	 */
	public function __construct(IResource $resource, $format = 'c')
	{
		parent::__construct($resource);
		$this->format = $format;
	}


	/**
	 * Get data
	 * @return array|\stdClass|\Traversable
	 */
	public function getData()
	{
		$data = parent::getData();
		$data = $this->parseDateTime($data);
		return $data;
	}

	/**
	 * @param $array
	 * @return array
	 */
	private function parseDateTime($array)
	{
		if ($array instanceof Traversable) {
			$array = iterator_to_array($array);
		}

		if (!is_array($array)) {
			return $array instanceof DateTime ? $array->format($this->format) : $array;
		}

		foreach ($array as $key => $value) {
			if ($value instanceof Traversable || is_array($array)) {
				$array[$key] = $this->parseDateTime($value);
			}
		}
		return $array;
	}

}