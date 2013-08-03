<?php
namespace Drahak\Restful\Http;

use Drahak\Restful\InvalidStateException;
use Drahak\Restful\Mapping\IMapper;
use Drahak\Restful\Mapping\MapperContext;
use Drahak\Restful\Validation\IValidationScopeFactory;
use Drahak\Restful\Application\BadRequestException;
use Nette\Object;

/**
 * InputFactory
 * @package Drahak\Restful\Http
 * @author Drahomír Hanák
 */
class InputFactory extends Object
{

	/** @var IRequest */
	protected $httpRequest;

	/** @var IValidationScopeFactory */
	private $validationScopeFactory;

	/** @var IMapper */
	private $mapper;

	/** @var MapperContext */
	private $mapperContext;

	/**
	 * @param IRequest $httpRequest
	 * @param MapperContext $mapperContext
	 * @param IValidationScopeFactory $validationScopeFactory
	 */
	public function __construct(IRequest $httpRequest, MapperContext $mapperContext, IValidationScopeFactory $validationScopeFactory)
	{
		$this->httpRequest = $httpRequest;
		$this->mapperContext = $mapperContext;
		$this->validationScopeFactory = $validationScopeFactory;
	}

	/**
	 * Create input
	 * @return Input
	 */
	public function create()
	{
		$input = new Input($this->validationScopeFactory);
		$input->setData($this->parseData());
		return $input;
	}

	/**
	 * Parse data for input
	 * @return array
	 *
	 * @throws BadRequestException
	 */
	protected function parseData()
	{
		$postQuery = (array)$this->httpRequest->getPost();
		$urlQuery = (array)$this->httpRequest->getQuery();
		$requestBody = $this->parseRequestBody();

		return array_merge($urlQuery, $requestBody, $postQuery);
	}

	/**
	 * Parse request body if any
	 * @return array|\Traversable
	 *
	 * @throws BadRequestException
	 */
	protected function parseRequestBody()
	{
		$requestBody = array();
		if ($input = file_get_contents('php://input')) {
			try {
				$this->mapper = $this->mapperContext->getMapper($this->httpRequest->getHeader('Content-Type'));
			} catch (InvalidStateException $e) {
				throw BadRequestException::unsupportedMediaType(
					'No mapper defined for Content-Type ' . $this->httpRequest->getHeader('Content-Type'),
					$e
				);
			}
			$requestBody = $this->mapper->parse($input);
		}
		return $requestBody;
	}

}
