<?php
namespace Drahak\Api;

/**
 * Resource data mapper interface
 * @package Drahak\Api\Mapping
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