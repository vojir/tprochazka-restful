<?php
namespace Drahak\Rest\Application;

use Drahak\Rest\IResourceFactory;
use Drahak\Rest\IResourcePresenter;
use Drahak\Rest\IResourceRouter;
use Drahak\Rest\IResponseFactory;
use Drahak\Rest\InvalidStateException;
use Drahak\Rest\IResource;
use Drahak\Rest\Resource;
use Nette\Utils\Strings;
use Nette\Application\UI;
use Nette\Application\IResponse;

/**
 * Base presenter for REST API presenters
 * @package Drahak\Rest\Application
 * @author Drahomír Hanák
 */
abstract class ResourcePresenter extends UI\Presenter implements IResourcePresenter
{

    /** @var string */
    protected $defaultMimeType = IResource::NULL;

    /** @var IResource */
    protected $resource;

    /** @var IResponseFactory */
    protected $responseFactory;

    /** @var IResourceFactory */
    protected $resourceFactory;

    /**
     * Inject response factory
     * @param IResponseFactory $responseFactory
     */
    public function injectResponseFactory(IResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Inject resource factory
     * @param IResourceFactory $resourceFactory
     */
    public function injectResourceFactory(IResourceFactory $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * Presenter startup
     */
    protected function startup()
    {
        parent::startup();
        $this->resource = $this->resourceFactory->create();

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