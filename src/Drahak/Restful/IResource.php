<?php
namespace Drahak\Restful;

/**
 * IResource determines REST service result set
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
interface IResource
{

	/** Result types */
	const XML = 'application/xml';
	const JSON = 'application/json';
	const QUERY = 'text/x-query';
	const DATA_URL = 'application/x-data-url';
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

	/**
	 * Delete resource data
	 * @return IResource
	 */
	public function delete();

}