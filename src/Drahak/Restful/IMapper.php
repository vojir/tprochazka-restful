<?php
namespace Drahak\Restful;

/**
 * Resource data mapper interface
 * @package Drahak\Restful\Mapping
 * @author Drahomír Hanák
 */
interface IMapper
{

    /**
     * Convert array or Traversable input to string output
     * @return mixed
     */
    public function convert();

}