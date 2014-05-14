<?php
namespace Drahak\Restful\Application;

use Drahak\Restful\Application\Responses\NullResponse;
use Drahak\Restful\InvalidArgumentException;
use Drahak\Restful\InvalidStateException;
use Drahak\Restful\IResource;
use Drahak\Restful\Mapping\MapperContext;
use Drahak\Restful\Utils\RequestFilter;
use Nette\Utils\Strings;
use Nette\Http\IResponse;
use Nette\Http\IRequest;
use Nette\Http\Url;
use Nette\Object;

/**
 * REST ResponseFactory
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
class ResponseFactory extends Object implements IResponseFactory
{

	/** @var IResponse */
	private $response;

	/** @var IRequest */
	private $request;

	/** @var MapperContext */
	private $mapperContext;

	/** @var ICacheValidator */
	private $cacheValidator;

	/** @var string JSONP request key */
	private $jsonp;

	/** @var array */
	private $responses = array(
		IResource::JSON => 'Drahak\Restful\Application\Responses\TextResponse',
		IResource::JSONP => 'Drahak\Restful\Application\Responses\JsonpResponse',
		IResource::QUERY => 'Drahak\Restful\Application\Responses\TextResponse',
		IResource::XML => 'Drahak\Restful\Application\Responses\TextResponse',
		IResource::DATA_URL => 'Drahak\Restful\Application\Responses\TextResponse',
		IResource::FILE => 'Drahak\Restful\Application\Responses\FileResponse',
		IResource::NULL => 'Drahak\Restful\Application\Responses\NullResponse'
	);

	/**
	 * @param string|boolean $jsonp key or FALSE if disabled
	 * @param IResponse $response
	 * @param IRequest $request
	 * @param MapperContext $mapperContext
	 */
	public function __construct($jsonp, IResponse $response, IRequest $request, MapperContext $mapperContext)
	{
		$this->response = $response;
		$this->request = $request;
		$this->mapperContext = $mapperContext;
		$this->jsonp = $jsonp;
	}

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
	 * Unregister API response from factory
	 * @param string $mimeType
	 */
	public function unregisterResponse($mimeType)
	{
		unset($this->responses[$mimeType]);
	}

	/**
	 * Set HTTP response
	 * @param IResponse $response
	 * @return ResponseFactory
	 */
	public function setHttpResponse(IResponse $response)
	{
		$this->response = $response;
		return $this;
	}

	/**
	 * Create new api response
	 * @param IResource $resource
	 * @param string|null $contentType
	 * @return IResponse
	 *
	 * @throws InvalidStateException
	 */
	public function create(IResource $resource, $contentType = NULL)
	{
		if ($contentType === NULL) {
			$contentType = $this->jsonp === FALSE || !$this->request->getQuery($this->jsonp) ?
				$this->getPreferredContentType() :
				IResource::JSONP;
		}

		if (!isset($this->responses[$contentType])) {
			throw new InvalidStateException('Unregistered API response for ' . $contentType);
		}

		if (!class_exists($this->responses[$contentType])) {
			throw new InvalidStateException('API response class does not exist.');
		}

		if (!$resource->getData()) {
			$this->response->setCode(204); // No content
		}

		$responseClass = $this->responses[$contentType];
		$response = new $responseClass($resource->getData(), $this->mapperContext->getMapper($contentType), $contentType);
		return $response;
	}

	/**
	 * Set JSONP key
	 * @param stirng|boolean $jsonp 
	 */
	public function setJsonp($jsonp)
	{
		$this->jsonp = $jsonp;
		return $this;
	}

	/**
	 * Get JSONP key 
	 * @return string|boolean
	 */
	public function getJsonp()
	{
		return $this->jsonp;
	}

	/**
	 * Get preferred request content type
	 * @return string
	 * 
	 * @throws  InvalidStateException If Accept header is unknown
	 */
	private function getPreferredContentType()
	{
		$acceptHeader = $this->request->getHeader('Accept');
		$accept = explode(',', $acceptHeader);
		$acceptableTypes = array_keys($this->responses);
		foreach ($accept as $mimeType) {
			foreach ($acceptableTypes as $formatMime) {
				if (Strings::contains($mimeType, $formatMime)) {
					return $formatMime;
				}
			}
		}
		throw new InvalidStateException('Unknown Accept header: ' . $acceptHeader, 400);
	}

}
