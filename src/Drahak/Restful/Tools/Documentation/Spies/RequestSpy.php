<?php
namespace Drahak\Restful\Tools\Documentation\Spies;

use Drahak\Restful\Http\IRequest;
use Drahak\Restful\Http\Request;
use Nette\Http\FileUpload;
use Nette\Http\key;
use Nette\Http\UrlScript;
use Nette\Object;

/**
 * RequestSpy
 * @package Drahak\Restful\Tools\Documentation\Spies
 * @author Drahomír Hanák
 */
final class RequestSpy extends Object implements IRequest
{

	/** @var IRequest */
	private $request;

	/** @var array */
	private $accessedHeaders = array();

	public function __construct(IRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * Get accessed headers
	 * @return array
	 */
	public final function getAccessedHeaders()
	{
		return $this->accessedHeaders;
	}

	/**
	 * Set headers
	 * @param array $headers
	 */
	public function setHeaders(array $headers)
	{
		$this->accessedHeaders = $headers;
	}

	/**************** Request interface ****************/

	/**
	 * Returns URL object.
	 * @return UrlScript
	 */
	public function getUrl()
	{
		return $this->request->getUrl();
	}

	/**
	 * Returns variable provided to the script via URL query ($_GET).
	 * If no key is passed, returns the entire array.
	 * @param  string key
	 * @param  mixed  default value
	 * @return mixed
	 */
	public function getQuery($key = NULL, $default = NULL)
	{
		return $this->request->getQuery($key, $default);
	}

	/**
	 * Returns variable provided to the script via POST method ($_POST).
	 * If no key is passed, returns the entire array.
	 * @param  string key
	 * @param  mixed  default value
	 * @return mixed
	 */
	public function getPost($key = NULL, $default = NULL)
	{
		return $this->request->getPost($key, $default);
	}

	/**
	 * Returns uploaded file.
	 * @param  string key (or more keys)
	 * @return FileUpload
	 */
	public function getFile($key)
	{
		return $this->request->getFile($key);
	}

	/**
	 * Returns uploaded files.
	 * @return array
	 */
	public function getFiles()
	{
		return $this->request->getFiles();
	}

	/**
	 * Returns variable provided to the script via HTTP cookies.
	 * @param  string key
	 * @param  mixed  default value
	 * @return mixed
	 */
	public function getCookie($key, $default = NULL)
	{
		return $this->request->getCookie($key, $default);
	}

	/**
	 * Returns variables provided to the script via HTTP cookies.
	 * @return array
	 */
	public function getCookies()
	{
		return $this->request->getCookies();
	}

	/**
	 * Returns HTTP request method (GET, POST, HEAD, PUT, ...). The method is case-sensitive.
	 * @return string
	 */
	public function getMethod()
	{
		return $this->request->getMethod();
	}

	/**
	 * Checks HTTP request method.
	 * @param  string
	 * @return bool
	 */
	public function isMethod($method)
	{
		return $this->request->isMethod($method);
	}

	/**
	 * Return the value of the HTTP header. Pass the header name as the
	 * plain, HTTP-specified header name (e.g. 'Accept-Encoding').
	 * @param  string
	 * @param  mixed
	 * @return mixed
	 */
	public function getHeader($header, $default = NULL)
	{
		$value = $default;
		if (isset($this->accessedHeaders[$header])) {
			$value = $this->accessedHeaders[$header];
		}
		$this->accessedHeaders[$header] = $value;
		return $value;
	}

	/**
	 * Returns all HTTP headers.
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Since method could be overridden, this returns a true method name
	 * @return string
	 */
	public function getOriginalMethod()
	{
		return $this->request->getOriginalMethod();
	}

	/**
	 * Is this request JSONP
	 * @return bool
	 */
	public function isJsonp()
	{
		return $this->request->isJsonp();
	}

	/**
	 * Get JSONP parameter value
	 * @return string|NULL
	 */
	public function getJsonp()
	{
		return $this->request->getJsonp();
	}

	/**
	 * Is pretty print enabled
	 * @return bool
	 */
	public function isPrettyPrint()
	{
		return $this->request->isPrettyPrint();
	}

	/**
	 * Get preferred request content type
	 * @return string
	 */
	public function getPreferredContentType()
	{
		return $this->request->getPreferredContentType();
	}

	/**
	 * Is the request is sent via secure channel (https).
	 * @return bool
	 */
	public function isSecured()
	{
		return $this->request->isSecured();
	}

	/**
	 * Is AJAX request?
	 * @return bool
	 */
	public function isAjax()
	{
		return $this->request->isAjax();
	}

	/**
	 * Returns the IP address of the remote client.
	 * @return string
	 */
	public function getRemoteAddress()
	{
		return $this->request->getRemoteAddress();
	}

	/**
	 * Returns the host of the remote client.
	 * @return string
	 */
	public function getRemoteHost()
	{
		return $this->request->getRemoteHost();
	}


}