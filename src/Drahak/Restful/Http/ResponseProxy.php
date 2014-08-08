<?php
namespace Drahak\Restful\Http;

use Nette\Http\IResponse;
use Nette\InvalidArgumentException;
use Nette\Object;
use Nette\Http\Response;

/**
 * ResponseProxy
 * @package Drahak\Restful\Http
 * @author Drahomír Hanák
 */
class ResponseProxy extends Object implements IResponse
{

	/** @var Response */
	private $response;

	/** @var int */
	private $code;

	/**
	 * @param IResponse $response
	 */
	public function __construct(IResponse $response)
	{
		$this->response = $response;
	}

	/**
	 * Set response
	 * @param IResponse $response
	 * @return ResponseProxy
	 */
	public function setResponse(IResponse $response)
	{
		$this->response = $response;
		return $this;
	}

	/**
	 * Set response code
	 * @param int $code
	 * @return ResponseProxy
	 *
	 * @throws InvalidArgumentException
	 */
	public function setCode($code)
	{
		try {
			$this->response->setCode($code);
			$this->code = $code;
		} catch (InvalidArgumentException $e) {
			if ($code > 99 && $code < 600) {
				$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
				header($protocol . ' ' . $code, TRUE, $code);
				$this->code = $code;
			} else {
				throw $e;
			}
		}
		return $this;
	}

	/**
	 * Returns status code
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * Set HTTP header
	 * @param string $name
	 * @param string|int|null $value
	 * @return ResponseProxy
	 */
	public function setHeader($name, $value)
	{
		$this->response->setHeader($name, $value);
		return $this;
	}

	/**
	 * Add HTTP header
	 * @param string $name
	 * @param string|int|null $value
	 * @return ResponseProxy
	 */
	public function addHeader($name, $value)
	{
		$this->response->addHeader($name, $value);
		return $this;
	}

	/**
	 * Set Content-Type header
	 * @param string $type
	 * @param string|null $charset
	 * @return ResponseProxy
	 */
	public function setContentType($type, $charset = NULL)
	{
		$this->response->setContentType($type, $charset);
		return $this;
	}

	/**
	 * Redirects to a new URL
	 * @param string $url
	 * @param int $code
	 * @return ResponseProxy
	 */
	public function redirect($url, $code = self::S302_FOUND)
	{
		$this->response->redirect($url, $code);
		return $this;
	}

	/**
	 * Sets the number of seconds before a page cached on a browser expires.
	 * @param mixed $seconds timestamp or number of seconds
	 * @return ResponseProxy
	 */
	public function setExpiration($seconds)
	{
		$this->response->setExpiration($seconds);
		return $this;
	}

	/**
	 * Checks if headers have been sent.
	 * @return bool
	 */
	public function isSent()
	{
		return $this->response->isSent();
	}

	/**
	 * Returns a list of headers to sent.
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->response->getHeaders();
	}

	/**
	 * Sends a cookie
	 * @param string $name of the cookie
	 * @param string $value
	 * @param int $expire expiration as unix timestamp or number of seconds; Value 0 means "until the browser is closed"
	 * @param string|null $path
	 * @param string|null $domain
	 * @param bool|null $secure
	 * @param bool|null $httpOnly
	 * @return ResponseProxy
	 */
	public function setCookie($name, $value, $expire, $path = NULL, $domain = NULL, $secure = NULL, $httpOnly = NULL)
	{
		$this->response->setCookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
		return $this;
	}

	/**
	 * Delete cookies
	 * @param string $name
	 * @param string|null $path
	 * @param string|null $domain
	 * @param bool|null $secure
	 * @return ResponseProxy
	 */
	public function deleteCookie($name, $path = NULL, $domain = NULL, $secure = NULL)
	{
		$this->response->deleteCookie($name, $path, $domain, $secure);
		return $this;
	}

	/**
	 * Removes duplicate cookies from response.
	 * @return ResponseProxy
	 */
	public function removeDuplicateCookies()
	{
		$this->response->removeDuplicateCookies();
		return $this;
	}

	/**
	 * Calls response class methods
	 * @param  string $name 
	 * @param  array $args 
	 * @return mixed
	 */
	public function __call($name, $args) {
        if ($this->response->getReflection()->hasMethod($name)) {
            return call_user_func_array(array($this->response, $name), $args);
        } else {
            return parent::__call($name, $args);
        }
	}
}
