<?php
namespace Drahak\Api\Application\Routes;

use Drahak\Api\IResourceRouter;
use Nette\Application\Routers\Route;
use Nette\Http;

/**
 * ResourceRoute
 * @package Drahak\Api\Routes
 * @author Drahomír Hanák
 *
 * @property-read string $method
 */
class ResourceRoute extends Route implements IResourceRouter
{

    /** @var array */
    private $methodDictionary = array(
        Http\IRequest::GET => self::GET,
        Http\IRequest::POST => self::POST,
        Http\IRequest::PUT => self::PUT,
        Http\IRequest::HEAD => self::HEAD,
        Http\IRequest::DELETE => self::DELETE
    );

    /** @var array */
    private $actionDictionary;

    /**
     * Is this route mapped to given method
     * @param int $method
     * @return bool
     */
    public function isMethod($method)
    {
        return ($this->flags & $method) == $method;
    }

    /**
     * @param string $mask
     * @param array|string $metadata
     * @param int $flags
     */
    public function __construct($mask, $metadata = array(), $flags = 0)
    {
        parent::__construct($mask, $metadata, $flags);

        if (isset($metadata['action']) && is_array($metadata['action'])) {
            $this->actionDictionary = $metadata['action'];
        }
    }

    /**
     * @param Http\IRequest $httpRequest
     * @return \Nette\Application\Request|NULL
     */
    public function match(Http\IRequest $httpRequest)
    {
        $appRequest = parent::match($httpRequest);
        if (!$appRequest) {
            return NULL;
        }

        // Check requested method
        $methodFlag = $this->methodDictionary[$httpRequest->getMethod()];
        if (!$this->isMethod($methodFlag)) {
            return NULL;
        }

        // If there is action dictionary, set method
        if ($this->actionDictionary) {
            if (!isset($this->actionDictionary[$methodFlag])) {
                return NULL;
            }

            $parameters = $appRequest->getParameters() + array('action' => $this->actionDictionary[$methodFlag]);
            $appRequest->setParameters($parameters);
        }

        return $appRequest;
    }

}