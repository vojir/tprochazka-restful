<?php
namespace Drahak\Restful\Http;

use Nette;

/**
 * HTTP Request
 * @package Drahak\Restful\Http
 * @author Drahomír Hanák
 */
class Request extends Nette\Http\Request implements IRequest
{

	/** Request method header override name */
	const METHOD_OVERRIDE_HEADER = 'X-HTTP-Method-Override';

	/** Request method override query parameter name */
	const METHOD_OVERRIDE_PARAM = '__method';

	/**
	 * Get request method
	 * @return string
	 */
	public function getMethod()
	{
		$method = parent::getMethod();
		if ($method !== self::POST) {
			return $method;
		}

		// Override request method with query param
		if ($this->getQuery(self::METHOD_OVERRIDE_PARAM)) {
			return $this->getQuery(self::METHOD_OVERRIDE_PARAM);
		}

		// Override request method with header
		if ($this->getHeader(self::METHOD_OVERRIDE_HEADER)) {
			return $this->getHeader(self::METHOD_OVERRIDE_HEADER);
		}
		return $method;
	}

	/**
	 * Get original method
	 * @return string
	 */
	public function getOriginalMethod()
	{
		return parent::getMethod();
	}

}