<?php
namespace Drahak\Restful;

use Drahak\Restful\Http\IRequest;
use Drahak\Restful\Converters\ResourceConverter;
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
		$resource = new ConvertedResource($this->resourceConverter);
		$resource->setContentType($this->request->getPreferredContentType());
		return $resource;
	}

}
