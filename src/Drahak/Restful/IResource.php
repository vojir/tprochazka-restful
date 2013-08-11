<?php
namespace Drahak\Restful;

/**
 * IResource determines REST service result set
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
interface IResource extends IResourceElement
{

	/** Result types */
	const XML = 'application/xml';
	const JSON = 'application/json';
	const JSONP = 'application/javascript';
	const QUERY = 'application/x-www-form-urlencoded';
	const DATA_URL = 'application/x-data-url';
	const FILE = 'application/octet-stream';
	const FORM = 'multipart/form-data';
	const NULL = 'NULL';

	/**
	 * Get content type
	 * @return string
	 */
	public function getContentType();

	/**
	 * Set content type
	 * @param string $contentType
	 * @return void
	 */
	public function setContentType($contentType);

}
