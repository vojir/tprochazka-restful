<?php
namespace Drahak\Restful\Tools\Documentation\Spies;

use Drahak\Restful\Http\charset;
use Drahak\Restful\Http\header;
use Drahak\Restful\Http\mime;
use Drahak\Restful\Http\ResponseProxy;

/**
 * ResponseSpy
 * @package Drahak\Restful\Tools\Documentation\Spies
 * @author Drahomír Hanák
 */
class ResponseSpy extends ResponseProxy
{

	/** @var int */
	private $code;

	/** @var string */
	private $contentType;

	/** @var array */
	private $headers;

	/**
	 * Set header
	 * @param string $name
	 * @param mixed $value
	 * @return ResponseSpy
	 */
	public function setHeader($name, $value)
	{
		$this->headers[$name] = $value;
		return $this;
	}

	/**
	 * Get accessed headers
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Set code
	 * @param int $code
	 * @return ResponseSpy
	 */
	public function setCode($code)
	{
		$this->code = $code;
		return $this;
	}

	/**
	 * Set content type
	 * @param string $type
	 * @param string|null $charset
	 * @return ResponseSpy
	 */
	public function setContentType($type, $charset = NULL)
	{
		$this->contentType = $type . ($charset ? '; charset=' . $charset : '');
		return $this;
	}

	/**
	 * Get content type
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

}