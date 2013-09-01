<?php
namespace Drahak\Restful\Http\Caching;

use Drahak\Restful\IResource;

/**
 * HTTP Cache validator interface
 * @package Drahak\Restful\Http\Caching
 * @author Drahomír Hanák
 */
interface ICacheValidator
{

	/**
	 * Does cache validator match given request
	 * @param IResource $resource
	 * @return string checksum
	 */
	public function match(IResource $resource);

	/**
	 * Generate hash validator checksum
	 * @param IResource $resource
	 * @return string checksum
	 */
	public function generate(IResource $resource);

	/**
	 * Get cache validator name
	 * @return string
	 */
	public function getName();

}