<?php
namespace Drahak\Restful\Http\Caching;

use Drahak\Restful\Http\IRequest;
use Drahak\Restful\IResource;
use Nette\Object;
use Nette\Utils\Json;

/**
 * ETag cache validator
 * @package Drahak\Restful\Http\Caching
 * @author Drahomír Hanák
 */
class EntityTagValidator extends Object implements ICacheValidator
{

	/** Validator name */
	const NAME = 'ETag';

	/** @internal Match header name */
	const MATCH_HEADER = 'If-None-Match';

	/** @internal ETag content hash algorithm */
	const ALGORITHM = 'md5';

	/** @var IRequest */
	private $request;

	/**
	 * @param IRequest $request
	 */
	public function __construct(IRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * Generate cache validator for resource
	 * @param IResource $resource
	 * @return string
	 */
	public function generate(IResource $resource)
	{
		$body = is_array($resource->getData()) ? Json::encode($resource->getData()) : $resource->getData();
		return hash(self::ALGORITHM, $body);
	}

	/**
	 * Does cache validator match given request
	 * @param IResource $resource
	 * @return string
	 */
	public function match(IResource $resource)
	{
		if ($this->request->getHeader(self::MATCH_HEADER)) {
			$header = $this->request->getHeader(self::MATCH_HEADER);
			$expected = $this->generate($resource);
			return $header === $expected ? $expected : NULL;
		}
		return NULL;
	}

	/**
	 * Get cache validator name
	 * @return string
	 */
	public function getName()
	{
		return self::NAME;
	}

}