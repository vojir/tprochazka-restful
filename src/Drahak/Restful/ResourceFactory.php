<?php
namespace Drahak\Restful;

use Drahak\Restful\Converters\ResourceConverter;
use Drahak\Restful\Utils\Strings;
use Nette\Http\IRequest;
use Nette\Object;

/**
 * ResourceFactory
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
class ResourceFactory extends Object implements IResourceFactory
{

	/** @var IRequest */
	private $request;

	/** @var ResourceConverter */
	private $resourceConverter;

	/** @var string[] */
	protected $accept = array(
		IResource::JSON,
		IResource::XML,
		IResource::JSONP,
		IResource::QUERY,
		IResource::DATA_URL
	);

	/**
	 * @param IRequest $request
	 * @param ResourceConverter $resourceConverter
	 */
	public function __construct(IRequest $request, ResourceConverter $resourceConverter)
	{
		$this->request = $request;
		$this->resourceConverter = $resourceConverter;
	}

	/**
	 * Create new API resource
	 * @param array $data
	 * @param stirng $defaultContentType
	 * @return IResource
	 *
	 * @throws  InvalidStateException If Accept header is unknown
	 */
	public function create(array $data = array(), $defaultContentType = NULL)
	{
		$resource = new ConvertedResource($this->resourceConverter, $data);
		try {
			$resource->setContentType($this->getPreferredContentType());
			return $resource;
		} catch (InvalidStateException $e) {
			if ($defaultContentType !== NULL) {
				$resource->setContentType($defaultContentType);
				return $resource;
			}
			throw $e;
		}
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
		foreach ($accept as $mimeType) {
			foreach ($this->accept as $formatMime) {
				if (Strings::contains($mimeType, $formatMime)) {
					return $formatMime;
				}
			}
		}
		throw new InvalidStateException('Unknown Accept header: ' . $acceptHeader, 400);
	}

}
