<?php
namespace Drahak\Restful\Tools\Documentation;

use Drahak\Restful\Tools\Documentation\Spies\InputSpy;
use Drahak\Restful\Utils\Strings;
use Nette\Http\IResponse;
use Nette\Object;
use Nette\Reflection\Method;
use Nette\Utils\Json;
use Nette\Utils\Neon;

/**
 * ResourceFactory
 * @package Drahak\Restful\Tools\Documentation
 * @author Drahomír Hanák
 */
class ResourceFactory extends Object
{

	/** @var IRequestRunner */
	private $requestRunner;

	/** @var ServiceSpy */
	private $serviceSpy;

	/** @var string */
	private $routePrefix;

	public function __construct($routePrefix, IRequestRunner $requestRunner, ServiceSpy $serviceSpy)
	{
		$this->routePrefix = $routePrefix;
		$this->requestRunner = $requestRunner;
		$this->serviceSpy = $serviceSpy;
	}

	/**
	 * @param Method $method
	 * @return Resource
	 *
	 * @throws InvalidExampleRequestException
	 */
	public function createResourceDoc(Method $method)
	{
		$exampleRequest = $this->getExampleRequest($method);

		$data = (array)$method->getAnnotation('example-data');
		$headers = (array)$method->getAnnotation('example-header');

		$input = $this->serviceSpy->on('Drahak\Restful\IInput', 'Drahak\Restful\Tools\Documentation\Spies\InputSpy');
		$request = $this->serviceSpy->on('Drahak\Restful\Http\IRequest', 'Drahak\Restful\Tools\Documentation\Spies\RequestSpy');
		$response = $this->serviceSpy->on('Nette\Http\IResponse', 'Drahak\Restful\Tools\Documentation\Spies\ResponseSpy');
		if ($data) $input->setData($data);
		if ($headers) $request->setHeaders($headers);

		$responseData = $this->requestRunner->run(implode(' ', $exampleRequest), $data);

		// Create resource entity
		$resource = new Resource;
		$resource->title = $method->getDescription();
		$resource->description = $method->getDescription();
		$resource->method = $exampleRequest[0];
		$resource->path = $exampleRequest[1];

		// Response
		$resource->response['data'] = $responseData;
		$resource->response['status'] = $response->getCode();
		$resource->response['headers'] = $response->getHeaders();

		// Request
		$resource->request['headers'] = $request->getAccessedHeaders();
		foreach ($input->getAccessedFields() as $field) {
			$resource->request['parameters'] = $input->getParameter($field);
		}

		return $resource;
	}

	/**
	 * Get example request as array(method, url)
	 * @param Method $method
	 * @return array
	 *
	 * @throws InvalidExampleRequestException
	 */
	protected function getExampleRequest(Method $method)
	{
		$exampleRequest = explode(' ', Strings::trim($method->getAnnotation('example')));
		if (!$exampleRequest) {
			throw new InvalidExampleRequestException('Annotation @example not found in ' . $method->getName());
		}

		if (count($exampleRequest) !== 2) {
			throw new InvalidExampleRequestException('Example request must be in format: METHOD /resource/name');
		}

		$url = Strings::substring($exampleRequest[1], 0, 1) === '/' ? Strings::substring($exampleRequest[1], 1) : $exampleRequest[1];
		if (Strings::substring($url, 0, strlen($this->routePrefix)) !== $this->routePrefix) {
			$exampleRequest[1] = $this->routePrefix . $exampleRequest[1];
		}

		return $exampleRequest;
	}


}