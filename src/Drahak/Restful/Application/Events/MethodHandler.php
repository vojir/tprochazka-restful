<?php
namespace Drahak\Restful\Application\Events;

use Drahak\Restful\Application\MethodOptions;
use Drahak\Restful\Application\BadRequestException;
use Drahak\Restful\Application\Routes\ResourceRoute;
use Drahak\Restful\Http\Request;
use Nette\Application\Application;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\Object;

/**
 * MethodHandler
 * @package Drahak\Restful\Application
 * @author Drahomír Hanák
 */
class MethodHandler extends Object implements IApplicationEvent
{

	/** @var IRequest */
	private $request;

	/** @var MethodOptions */
	private $methods;

	/**
	 * @param IRequest $request
	 * @param MethodOptions $methods
	 */
	public function __construct(IRequest $request, MethodOptions $methods)
	{
		$this->request = $request;
		$this->methods = $methods;
	}

	/**
	 * On application run
	 * @param Application $application
	 *
	 * @throws BadRequestException
	 */
	public function run(Application $application)
	{
		$router = $application->getRouter();
		$response = $router->match($this->request);
		if (!$response) {
			$methods = $this->methods->getOptions($this->request->getUrl());
			if (!$methods) return;
			throw BadRequestException::methodNotSupported(
				'Method not supported. Available methods: ' . implode(', ', $methods));
		}
	}

}
