<?php
namespace Drahak\Restful\Resource;

/**
 * Converts resource or input data to some format or stringify objects
 * @package Drahak\Restful\Resource
 */
interface IConverter
{

    /**
     * Converts data from resource to output
     * @param array $resource
     * @return array
     */
    public function convert(array $resource);

}
