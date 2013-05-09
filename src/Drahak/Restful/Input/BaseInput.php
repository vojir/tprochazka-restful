<?php
namespace Drahak\Restful\Input;

use IteratorAggregate;
use Drahak\Restful\IInput;
use Drahak\Restful\IMapper;
use Nette\MemberAccessException;
use Nette\Object;
use Nette\Http;

/**
 * Abstract base Input
 * @package Drahak\Restful\Input
 * @author Drahomír Hanák
 *
 * @property-write IMapper $mapper
 */
abstract class BaseInput extends Object implements IteratorAggregate, IInput
{

	/** @var \Nette\Http\IRequest */
	protected $httpRequest;

	/** @var IMapper */
	protected $mapper;

	public function __construct(Http\IRequest $httpRequest)
	{
		$this->httpRequest = $httpRequest;
	}

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
	 * This gets PHP input to read PUT, DELETE and HEAD request methods
	 * @return string
	 */
	protected function getPhpInput()
	{
		$put = fopen('php://input', 'r');
		$request = '';
		while($data = fread($put, 1024)) {
			$request .= $data;
		}
		fclose($put);
		return $request;
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
			parent::__get($name);
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
		return $isset ? $isset : isset($this->getData()[$name]);
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

}