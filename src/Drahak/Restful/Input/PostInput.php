<?php
namespace Drahak\Restful\Input;


use Nette\Http\IRequest;

/**
 * PostInput
 * @package Drahak\Restful\Input
 * @author Drahomír Hanák
 *
 * @property-read array $data
 */
class PostInput extends BaseInput
{

	/** @var array */
	private $data;

	/**
	 * Get data
	 * @return array
	 */
	public function getData()
	{
		if (!$this->data) {
			$this->data = $this->httpRequest->getPost();
		}
		return $this->data;
	}

	/**
	 * Get input method name
	 * @return string
	 */
	public function getMethod()
	{
		return IRequest::POST;
	}


}