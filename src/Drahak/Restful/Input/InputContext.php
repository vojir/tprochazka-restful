<?php
namespace Drahak\Restful\Input;

use Drahak\Restful\IInput;
use Drahak\Restful\IMapper;
use Drahak\Restful\InvalidStateException;
use Nette\Object;
use Nette\Http;

/**
 * InputContext
 * @package Drahak\Restful\Input
 * @author Drahomír Hanák
 */
class InputContext extends Object
{

	/** @var array */
	private $input = array();

	/** @var \Nette\Http\IRequest */
	private $httpRequest;

	public function __construct(Http\IRequest $httpRequest)
	{
		$this->httpRequest = $httpRequest;
	}

	/**
	 * Add input to context
	 * @param IInput $input
	 * @return InputContext
	 */
	public function addInput(IInput $input)
	{
		$this->input[$input->getMethod()] = $input;
		return $this;
	}

	/**
	 * Remove input
	 * @param IInput $input
	 * @return InputContext
	 */
	public function removeInput(IInput $input)
	{
		unset($this->input[$input->getMethod()]);
		return $this;
	}

	/**
	 * Get current input
	 * @return IInput
	 * @throws \Drahak\Restful\InvalidStateException
	 */
	public function getCurrent()
	{
		if (!isset($this->input[$this->httpRequest->getMethod()])) {
			throw new InvalidStateException('Input parser for request method ' . $this->httpRequest->getMethod() . ' is not defined');
		}
		return $this->input[$this->httpRequest->getMethod()];
	}

	/******************** IInput interface ********************/

	/**
	 * Get parsed input data
	 * @return mixed
	 */
	public function getData()
	{
		return $this->getCurrent()->getData();
	}

	/**
	 * Get input method name
	 * @return string
	 */
	public function getMethod()
	{
		return $this->getCurrent()->getMethod();
	}

	/**
	 * Set input mapper
	 * @param IMapper $mapper
	 * @return IInput
	 */
	public function setMapper(IMapper $mapper)
	{
		return $this->getCurrent()->setMapper($mapper);
	}


}