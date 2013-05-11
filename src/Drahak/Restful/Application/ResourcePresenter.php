<?php
namespace Drahak\Restful\Application;

use Drahak\Restful\IInput;
use Drahak\Restful\IRequestAuthenticator;
use Drahak\Restful\IResourcePresenter;
use Drahak\Restful\IResponseFactory;
use Drahak\Restful\InvalidStateException;
use Drahak\Restful\IResource;
use Drahak\Restful\Resource;
use Drahak\Restful\Security\SecurityException;
use Drahak\Restful\Security\UnauthorizedRequestException;
use Nette\Utils\Strings;
use Nette\Application;
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

	/** @var IRequestAuthenticator */
	protected $requestAuthenticator;

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
	 * Inject API request authenticator
	 * @param IRequestAuthenticator $authenticator
	 */
	public function injectRequestAuthenticator(IRequestAuthenticator $authenticator)
	{
		$this->requestAuthenticator = $authenticator;
	}

	/**
	 * Inject input
	 * @param IInput $input
	 */
	public function injectInput(IInput $input)
	{
		$this->input = $input;
	}

	/**
	 * Presenter startup
	 */
	protected function startup()
	{
		parent::startup();

		if ($this->defaultMimeType) {
			$this->resource->setMimeType($this->defaultMimeType);
		}

		$this->requestAuthenticator->authenticate($this->input);
	}

	/**
	 * @param Application\Request $request
	 * @return IResponse|void
	 *
	 * @throws UnauthorizedRequestException|\Exception
	 */
	public function run(Application\Request $request)
	{
		try {
			parent::run($request);
		} catch (UnauthorizedRequestException $e) {
			if ($this->isProduction()) {
				$this->resource->delete();
				$this->resource->error = 'API request is not authorized.';
				$this->sendResource();
			} else {
				throw $e;
			}
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

	/**
	 * Is debug mode disabled
	 * @return bool
	 */
	private function isProduction()
	{
		return $this->context->parameters['debugMode'];
	}

}