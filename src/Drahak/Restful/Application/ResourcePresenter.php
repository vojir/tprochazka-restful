<?php
namespace Drahak\Restful\Application;

use Drahak\Restful\IInput;
use Drahak\Restful\IMapper;
use Drahak\Restful\IResourceFactory;
use Drahak\Restful\IResourcePresenter;
use Drahak\Restful\IResponseFactory;
use Drahak\Restful\Input\InputContext;
use Drahak\Restful\InvalidStateException;
use Drahak\Restful\IResource;
use Drahak\Restful\Mapping\JsonMapper;
use Drahak\Restful\Resource;
use Nette\Utils\Strings;
use Nette\Application\UI;
use Nette\Application\IResponse;

/**
 * Base presenter for REST API presenters
 * @package Drahak\Restful\Application
 * @author Drahomír Hanák
 */
abstract class ResourcePresenter extends UI\Presenter implements IResourcePresenter
{

	/** @var string */
	protected $defaultMimeType = IResource::NULL;

	/** @var IResource */
	protected $resource;

	/** @var IInput */
	protected $input;

	/** @var IResponseFactory */
	protected $responseFactory;

	/** @var IMapper */
	protected $mapper;

	/** @var InputContext */
	private $inputContext;

	/**
	 * Inject response factory
	 * @param IResponseFactory $responseFactory
	 */
	public function injectResponseFactory(IResponseFactory $responseFactory)
	{
		$this->responseFactory = $responseFactory;
	}

	/**
	 * Inject resource
	 * @param IResource $resource
	 */
	public function injectResource(IResource $resource)
	{
		$this->resource = $resource;
	}

	/**
	 * Inject input strategy context
	 * @param InputContext $inputContext
	 */
	public function injectInputContext(InputContext $inputContext)
	{
		$this->inputContext = $inputContext;
	}

	/**
	 * Inject json input mapper by default
	 * @param JsonMapper $mapper
	 */
	public function injectJsonMapper(JsonMapper $mapper)
	{
		$this->mapper = $mapper;
	}

	/**
	 * Presenter startup
	 */
	protected function startup()
	{
		parent::startup();

		$this->input = $this->inputContext->getCurrent();
		$this->input->setMapper($this->mapper);
		if ($this->defaultMimeType) {
			$this->resource->setMimeType($this->defaultMimeType);
		}
	}

	/**
	 * On before render
	 */
	protected function beforeRender()
	{
		parent::beforeRender();
		$this->sendResource();
	}

	/**
	 * Get REST API response
	 * @param string $mimeType
	 * @return IResponse
	 *
	 * @throws InvalidStateException
	 */
	public function sendResource($mimeType = NULL)
	{
		if ($mimeType !== NULL) {
			$this->resource->setMimeType($mimeType);
		}

		$response = $this->responseFactory->create($this->resource);
		$this->sendResponse($response);
	}

}