<?php
namespace Drahak\Restful\Http;

use Nette;

/**
 * HTTP API request interface
 * @package Drahak\Restful\Http
 * @author Drahomír Hanák
 */
interface IRequest extends Nette\Http\IRequest
{

	/** Patch request method */
	const PATCH = 'PATCH';

	/**
	 * Since method could be overridden, this returns a true method name
	 * @return string
	 */
	public function getOriginalMethod();

}