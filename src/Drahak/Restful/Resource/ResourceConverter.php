<?php
namespace Drahak\Restful\Resource;

use Nette\Object;

/**
 * ResourceConverter
 * @package Drahak\Restful\Resource
 *
 * @property-read IConverter[] $converter
 */
class ResourceConverter extends Object
{

	/** @var IConverter[] */
	private $converters = array();

	/**
	 * Get converters
	 * @return IConverter[]
	 */
	public function getConverters()
	{
		return $this->converters;
	}

	/**
	 * Converts data from resource using converters
	 * @param array $data
	 * @return array
	 */
	public function convert(array $data)
	{
		/** @var IConverter $converter */
		foreach ($this->converters as $converter) {
			$data = $converter->convert($data);
		}

		return $data;
	}

}