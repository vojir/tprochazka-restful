<?php
namespace Drahak\Restful\Input;

use Nette\Http;

/**
 * GetInput
 * @package Drahak\Restful\Input
 * @author Drahomír Hanák
 *
 * @property-read array $data
 */
class GetInput extends BaseInput
{

	/** @var array */
	private $data;

	/**
	 * Get parsed input data
	 * @return array
	 */
	public function getData()
	{
		if (!$this->data) {
			$this->data = $this->httpRequest->getQuery();
		}
		return $this->data;
	}

	/**
	 * Get input method name
	 * @return string
	 */
	public function getMethod()
	{
		return Http\IRequest::GET;
	}

}