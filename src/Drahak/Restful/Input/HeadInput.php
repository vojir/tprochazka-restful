<?php
namespace Drahak\Restful\Input;

use Nette\Http\IRequest;

/**
 * HeadInput
 * @package Drahak\Restful\Input
 * @author Drahomír Hanák
 *
 * @property-read array $data
 */
class HeadInput extends BaseInput
{

	/** @var array */
	private $data;

	/**
	 * Get parsed input data
	 * @return mixed
	 */
	public function getData()
	{
		if (!$this->data) {
			$this->data = $this->mapper->parseRequest($this->getPhpInput());
		}
		return $this->data;
	}

	/**
	 * Get input method name
	 * @return string
	 */
	public function getMethod()
	{
		return IRequest::HEAD;
	}

}