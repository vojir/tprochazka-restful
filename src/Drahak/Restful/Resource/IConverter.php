<?php
namespace Drahak\Restful\Resource;

/**
 * Converts resource data to some format or stringify objects
 * @package Drahak\Restful\Resource
 */
interface IConverter
{

    /**
     * Converts data from resource
     * @param array $resource
     * @return array
     */
    public function convert(array $resource);

}