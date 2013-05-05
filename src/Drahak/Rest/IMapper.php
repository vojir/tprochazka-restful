<?php
namespace Drahak\Rest;

/**
 * Resource data mapper interface
 * @package Drahak\Rest\Mapping
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