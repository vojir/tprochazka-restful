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
	 * @return IResource
	 */
	public function create(array $data = array())
	{
		$resource = new ConvertedResource($this->resourceConverter, $data);
		$resource->setContentType($this->getPreferredContentType());
		return $resource;
	}

	/**
	 * Get preferred request content type
	 * @return string
	 */
	private function getPreferredContentType()
	{
		$formats = array(
			'json' => IResource::JSON,
			'xml' => IResource::XML,
			'jsonp' => IResource::JSONP,
			'query' => IResource::QUERY,
			'data_url' => IResource::DATA_URL
		);
		$accept = explode(',', $this->request->getHeader('Accept'));
		foreach ($accept as $mimeType) {
			foreach ($formats as $formatMime) {
				if (Strings::contains($mimeType, $formatMime)) {
					return $formatMime;
				}
			}
		}
		return NULL;
	}

}
