<?php
namespace Drahak\Restful;

use Drahak\Restful\Mapping\MapperContext;
use Drahak\Restful\Validation\IDataProvider;
use Drahak\Restful\Validation\IValidationScope;
use Drahak\Restful\Validation\IValidationSchemaAggregate;
use Drahak\Restful\Validation\ValidationScopeFactory;
use IteratorAggregate;
use Drahak\Restful\IInput;
use Drahak\Restful\Mapping\IMapper;
use Nette\MemberAccessException;
use Nette\Object;
use Nette\Http;
use Nette\Utils\Json;
use Nette\Utils\Strings;

/**
 * Request Input parser
 * @package Drahak\Restful\Input
 * @author Drahomír Hanák
 *
 * @property-read array $data
 */
class Input extends Object implements IteratorAggregate, IInput, IDataProvider
{

	/** @var \Nette\Http\IRequest */
	private $httpRequest;

	/** @var array */
	private $data;

	/** @var IValidationScope */
	private $validationScope;

	/** @var ValidationScopeFactory */
	private $validationScopeFactory;

	/** @var IMapper */
	protected $mapper;

	/**
	 * @param Http\IRequest $httpRequest
	 * @param MapperContext $mapperContext
	 * @param ValidationScopeFactory $validationScopeFactory
	 */
	public function __construct(Http\IRequest $httpRequest, MapperContext $mapperContext, ValidationScopeFactory $validationScopeFactory)
	{
		$this->httpRequest = $httpRequest;
		$this->validationScopeFactory = $validationScopeFactory;
		try {
			$this->mapper = $mapperContext->getMapper($httpRequest->getHeader('Content-Type'));
		} catch (InvalidStateException $e) {
			// No mapper for this content type - ignore in this step
		}
	}

	/******************** IInput ********************/

	/**
	 * Set input mapper
	 * @param IMapper $mapper
	 * @return IInput
	 */
	public function setMapper(IMapper $mapper)
	{
		$this->mapper = $mapper;
		return $this;
	}

	/**
	 * Get parsed input data
	 * @return array
	 *
	 * @throws Application\BadRequestException when mapper for this Content-Type not found
	 */
	public function getData()
	{
		if (!$this->data) {
			$this->data = $this->parseData();
		}
		return $this->data;
	}

	/**
	 * Parse data from input
	 * @return array|mixed|\Traversable
	 * @throws Application\BadRequestException
	 */
	private function parseData()
	{
		if ($this->httpRequest->getPost()) {
			return $this->httpRequest->getPost();
		} else if ($input = file_get_contents('php://input')) {
			if (!$this->mapper) {
				throw Application\BadRequestException::unsupportedMediaType(
					'No mapper defined for Content-Type ' . $this->httpRequest->getHeader('Content-Type')
				);
			}
			return $this->mapper->parse($input);
		}
		return (array)$this->httpRequest->getQuery();
	}

	/**
	 * Set input data
	 * @param array $data
	 * @return Input
	 */
	public function setData(array $data)
	{
		$this->data = $data;
		return $this;
	}

	/******************** Magic methods ********************/

	/**
	 * @param string $name
	 * @return mixed
	 *
	 * @throws \Exception|\Nette\MemberAccessException
	 */
	public function &__get($name)
	{
		try {
			return parent::__get($name);
		} catch(MemberAccessException $e) {
			$data = $this->getData();
			if (isset($data[$name])) {
				return $data[$name];
			}
			throw $e;
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name)
	{
		$isset = parent::__isset($name);
		if ($isset) {
			return TRUE;
		}
		$data = $this->getData();
		return isset($data[$name]);
	}

	/******************** Iterator aggregate interface ********************/

	/**
	 * Get input data iterator
	 * @return InputIterator
	 */
	public function getIterator()
	{
		return new InputIterator($this);
	}

	/******************** Validation data provider interface ********************/

	/**
	 * Get validation field
	 * @param string $name
	 * @return Validation\IField
	 */
	public function field($name)
	{
		return $this->getValidationScope()->field($name);
	}

	/**
	 * Validate input data
	 * @return array
	 */
	public function validate()
	{
		return $this->getValidationScope()->validate($this->getData());
	}

	/**
	 * Is input valid
	 * @return bool
	 */
	public function isValid()
	{
		return !$this->validate();
	}

	/**
	 * Get validation scope
	 * @return IValidationScope
	 */
	public function getValidationScope()
	{
		if (!$this->validationScope) {
			$this->validationScope = $this->validationScopeFactory->create();
		}
		return $this->validationScope;
	}

}