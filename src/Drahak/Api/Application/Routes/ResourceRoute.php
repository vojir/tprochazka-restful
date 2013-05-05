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

    /** @var string */
    private $method = self::GET;

    /**
     * Get cuurrent route method
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @param array $mask
     * @param array $metadata
     * @param int $flags
     */
    public function __construct($method, $mask, $metadata = array(), $flags = 0)
    {
        parent::__construct($mask, $metadata, $flags);
        $this->method = $method;
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

        if ($httpRequest->getMethod() !== $this->getMethod()) {
            return NULL;
        }

        return $appRequest;
    }

}