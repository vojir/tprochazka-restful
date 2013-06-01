<?php
namespace Drahak\Restful\Http;

use Nette;

/**
 * RequestFactory
 * @package Drahak\Restful\Http
 * @author Drahomír Hanák
 */
class RequestFactory extends Nette\Http\RequestFactory
{

	/**
	 * Create HTTP request
	 * @return Request
	 */
	public function createHttpRequest()
	{
		$request = parent::createHttpRequest();
		return new Request(
			$request->url, $request->query, $request->post, $request->files, $request->cookies, $request->headers,
			$request->method, $request->remoteAddress, $request->remoteHost
		);
	}

}