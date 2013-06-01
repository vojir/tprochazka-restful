<?php
namespace Drahak\Restful\Http;

use Nette;

/**
 * HTTP Request
 * @package Drahak\Restful\Http
 * @author Drahomír Hanák
 */
class Request extends Nette\Http\Request
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
		if ($this->getQuery(self::METHOD_OVERRIDE_PARAM)) {
			return $this->getQuery(self::METHOD_OVERRIDE_PARAM);
		}

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