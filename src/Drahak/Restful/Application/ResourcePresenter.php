<?php
namespace Drahak\Restful\Application;

use Drahak\Restful\IInput;
use Drahak\Restful\IResponseFactory;
use Drahak\Restful\InvalidStateException;
use Drahak\Restful\IResource;
use Drahak\Restful\Resource;
use Drahak\Restful\Security\AuthenticationProcess;
use Drahak\Restful\Security\RequestAuthenticator;
use Drahak\Restful\Security\SecurityException;
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

	/** @var AuthenticationProcess */
	protected $authenticationProcess;

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
	 * @param AuthenticationProcess $authenticationProcess
	 */
	public function injectRequestAuthenticator(AuthenticationProcess $authenticationProcess)
	{
		$this->authenticationProcess = $authenticationProcess;
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
	}

	/**
	 * Check security requirements
	 * @param $element
	 */
	public function checkRequirements($element)
	{
		try {
			parent::checkRequirements($element);
		} catch (Application\ForbiddenRequestException $e) {
			$this->sendErrorResource($e);
		}

		try {
			$this->authenticationProcess->authenticate($this->input);
		} catch (SecurityException $e) {
			$this->sendErrorResource($e);
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
	 * @param int $code
	 * @return IResponse
	 *
	 * @throws InvalidStateException
	 */
	public function sendResource($mimeType = NULL, $code = 200)
	{
		if ($mimeType !== NULL) {
			$this->resource->setMimeType($mimeType);
		}

		$this->getHttpResponse()->setCode($code);

		$response = $this->responseFactory->create($this->resource, $code);
		$this->sendResponse($response);
	}

	/**
	 * Send error resource to output
	 * @param \Exception $e
	 */
	protected function sendErrorResource(\Exception $e)
	{
		$code = $e->getCode() ? $e->getCode() : 500;

		$this->resource->delete();
		$this->resource->status = 'error';
		$this->resource->code = $code;
		$this->resource->message = $e->getMessage();

		$this->sendResource($this->defaultMimeType, $code);
	}

}