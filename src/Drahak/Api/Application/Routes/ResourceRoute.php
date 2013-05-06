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
     * @param Http\IRequest $httpRequest
     * @return \Nette\Application\Request|NULL
     */
    public function match(Http\IRequest $httpRequest)
    {
        $appRequest = parent::match($httpRequest);
        if (!$appRequest) {
            return NULL;
        }

        $methodFlag = $this->methodDictionary[$httpRequest->getMethod()];
        if (!$this->isMethod($methodFlag)) {
            return NULL;
        }

        return $appRequest;
    }

}