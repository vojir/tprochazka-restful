<?php
namespace Drahak\Restful\Tools\Documentation;

use Drahak\Restful\Http\IRequest;
use Drahak\Restful\Http\Request;
use Drahak\Restful\InvalidStateException;
use Drahak\Restful\Utils\Strings;
use Nette\Application\IPresenterFactory;
use Nette\Application\IRouter;
use Nette\DI\Container;
use Nette\Http\Url;
use Nette\Object;

/**
 * RequestRunner
 * @package Drahak\Restful\Tools\Documentation
 * @author Drahomír Hanák
 */
class RequestRunner extends Object implements IRequestRunner
{

	/** @var IRouter */
	private $router;

	/** @var IRequest */
	private $request;

	/** @var IPresenterFactory */
	private $presenterFactory;

	/**
	 * @param IPresenterFactory $presenterFactory
	 * @param IRouter $router
	 * @param IRequest $request
	 */
	public function __construct(IPresenterFactory $presenterFactory, IRouter $router, IRequest $request)
	{
		$this->router = $router;
		$this->request = $request;
		$this->presenterFactory = $presenterFactory;
	}

	/**
	 * Run a fake request to get data
	 * @param string $requestString
	 * @param array $data
	 * @return array|\stdClass
	 */
	public function run($requestString, $data = array())
	{
		$appRequest = $this->getAppRequest($this->router, $requestString, $data);
		$presenter = $this->presenterFactory->createPresenter($appRequest->presenterName);
		$presenter->autoCanonicalize = FALSE;
		$response = $presenter->run($appRequest);
		return $response->getData();
	}

	/**
	 * Get service spy
	 * @return ServiceSpy
	 */
	public final function getServiceSpy()
	{
		return $this->serviceSpy;
	}

	/**
	 * Match route
	 * @param IRouter $router
	 * @param string $requestString
	 * @param array $data
	 * @return \Nette\Application\Request
	 *
	 * @throws InvalidStateException
	 */
	public function getAppRequest(IRouter $router, $requestString, $data = array())
	{
		$request = $this->createRequest($requestString, $data);

		/** @var IRouter $route */
		foreach ($router as $route) {
			if ($appRequest = $route->match($request)) {
				return $appRequest;
			}

			if (is_array($route) || $route instanceof \Traversable) {
				return $this->getAppRequest($route, $requestString);
			}
		}

		throw new InvalidStateException('Request ' . $requestString . ' does not match any route');
	}

	/**
	 * Get request from string
	 * @param string $requestString
	 * @param array $data
	 * @return Request
	 */
	protected function createRequest($requestString, $data = array())
	{
		$parts = explode(' ', $requestString);
		$path = Strings::substring($parts[1], 0, 1) == '/' ?
			Strings::substring($parts[1], 1) :
			$parts[1];

		$url = $this->request->getUrl();
		$url->path = $url->getBasePath() . $path;

		return new Request(
			$url, $this->request->query, $data, $this->request->files, $this->request->cookies, $this->request->headers,
			$parts[0], $this->request->remoteAddress, $this->request->remoteHost
		);
	}

}