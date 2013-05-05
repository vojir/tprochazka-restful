<?php
namespace Drahak\Rest;

/**
 * IResource determines REST service result set
 * @package Drahak\Rest
 * @author Drahomír Hanák
 */
interface IResource
{

    /** Result types */
    const XML = 'application/xml';
    const JSON = 'application/json';
    const TEXT = 'text/plain';
    const NULL = 'NULL';

    /**
     * Get mime type
     * @return string
     */
    public function getMimeType();

    /**
     * Get result set data
     * @return array|\stdClass|\Traversable
     */
    public function getData();

}