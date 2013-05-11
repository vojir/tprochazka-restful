<?php
namespace Drahak\Restful;

use Drahak\Restful\IResource;
use Nette\Application\IResponse;
use Nette\Object;

/**
 * REST ResponseFactory
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
class ResponseFactory extends Object implements IResponseFactory
{

	/** @var array */
	private $responses = array(
		IResource::JSON => 'Nette\Application\Responses\JsonResponse',
		IResource::TEXT => 'Drahak\Restful\Application\Responses\QueryResponse',
		IResource::XML => 'Drahak\Restful\Application\Responses\XmlResponse',
		IResource::NULL => 'Drahak\Restful\Application\Responses\NullResponse'
	);

	/**
	 * Register new response type to factory
	 * @param string $mimeType
	 * @param string $responseClass
	 * @return $this
	 *
	 * @throws InvalidArgumentException
	 */
	public function registerResponse($mimeType, $responseClass)
	{
		if (!class_exists($responseClass)) {
			throw new InvalidArgumentException('Response class does not exist.');
		}

		$this->responses[$mimeType] = $responseClass;
		return $this;
	}

	/**
	 * Unregister API response fro mfactory
	 * @param string $mimeType
	 */
	public function unregisterResponse($mimeType)
	{
		unset($this->responses[$mimeType]);
	}

	/**
	 * Create new API response
	 * @param IResource $resource
	 * @return IResponse
	 * @throws InvalidStateException
	 */
	public function create(IResource $resource)
	{
		$contentType = $resource->getMimeType();
		if (!isset($this->responses[$contentType])) {
			throw new InvalidStateException('Unregistred API response.');
		}

		if (!class_exists($this->responses[$contentType])) {
			throw new InvalidStateException('API response class does not exist.');
		}

		$response = new $this->responses[$contentType]($resource->getData());
		return $response;
	}

}