<?php
namespace Drahak\Restful\Application;

use Drahak\Restful\InvalidStateException;
use Nette\Application\UI\PresenterComponentReflection;
use Nette\Object;
use Nette\Reflection\ClassType;
use Nette\Utils\Strings;

/**
 * MethodAnnotation
 * @package Drahak\Restful\Annotations
 * @author Drahomír Hanák
 */
class MethodAnnotation extends Object
{

    /** @var ClassType */
    private $reflection;

    /** @var string */
    private $method;

    /** @var array */
    private $routes = array();

    public function __construct(ClassType $reflection, $method)
    {
        $this->reflection = $reflection;
        $this->method = $method;
    }

    /**
     * Create routes
     * @return array
     * @throws \Drahak\Restful\InvalidStateException
     */
    private function createRoutes()
    {
        $routes = array();
        $methods = $this->reflection->getMethods();
        foreach ($methods as $method) {
            if ($method->hasAnnotation($this->method)) {
                if (!Strings::contains($method->getName(), 'action')) {
                    throw new InvalidStateException(
                        'HTTP request method annotations (such as GET) can be assignet only to action<Action> methods'
                    );
                }
                $name = str_replace('action', '', $method->getName());
                $name = Strings::lower(Strings::substring($name, 0, 1)) . Strings::substring($name, 1);

                $destination = str_replace('Presenter', '', $this->reflection->getShortName()) . ':' . $name;
                if (isset($routes[$destination])) {
                    throw new InvalidStateException('Route to resource ' . $destination . ' already exists.');
                }
                $routes[$destination] = $method->getAnnotation($this->method);
            }
        }
        return $routes;
    }

    /**
     * Get resource presenter routes from annotation
     * @return array
     */
    public function getRoutes()
    {
        if (!$this->routes) {
            $this->routes = $this->createRoutes();
        }
        return $this->routes;
    }

}