<?php
namespace Drahak\Restful\Resource;

use Nette\Object;
use Traversable;
use DateTime;

/**
 * DateTimeConverter
 * @package Drahak\Restful\Resource
 * @author Drahomír Hanák
 */
class DateTimeConverter extends Object implements IConverter
{

	/** DateTime format */
	private $format = 'c';

	/**
	 * @param string $format of date time
	 */
	public function __construct($format = 'c')
	{
		$this->format = $format;
	}

    /**
     * Converts DateTime objects in resource to string
     * @param array $resource
     * @return array
     */
	public function convert(array $resource)
	{
		$data = $this->parseDateTime($resource);
		return $data;
	}

	/**
	 * @param $array
	 * @return array
	 */
	private function parseDateTime($array)
	{
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