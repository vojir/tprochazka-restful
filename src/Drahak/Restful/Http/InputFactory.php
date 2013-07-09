<?php
namespace Drahak\Restful\Http;

use Drahak\Restful\InvalidStateException;
use Drahak\Restful\Mapping\IMapper;
use Drahak\Restful\Mapping\MapperContext;
use Drahak\Restful\Validation\ValidationScopeFactory;
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

	/** @var ValidationScopeFactory */
	private $validationScopeFactory;

	/** @var IMapper */
	private $mapper;

	/** @var MapperContext */
	private $mapperContext;

	/**
	 * @param IRequest $httpRequest
	 * @param MapperContext $mapperContext
	 * @param ValidationScopeFactory $validationScopeFactory
	 */
	public function __construct(IRequest $httpRequest, MapperContext $mapperContext, ValidationScopeFactory $validationScopeFactory)
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
	 * @throws BadRequestException
	 */
	protected function parseData()
	{
		if ($this->httpRequest->getPost()) {
			return $this->httpRequest->getPost();
		} else if ($input = file_get_contents('php://input')) {
			try {
				$this->mapper = $this->mapperContext->getMapper($this->httpRequest->getHeader('Content-Type'));
			} catch (InvalidStateException $e) {
				throw BadRequestException::unsupportedMediaType(
					'No mapper defined for Content-Type ' . $this->httpRequest->getHeader('Content-Type'),
					$e
				);
			}
			return $this->mapper->parse($input);
		}
		return (array)$this->httpRequest->getQuery();
	}

}