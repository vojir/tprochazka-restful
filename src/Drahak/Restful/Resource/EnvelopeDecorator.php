<?php
namespace Drahak\Restful\Resource;

use Drahak\Restful\IDataResource;
use Nette\Http\IResponse;

/**
 * EnvelopeDecorator decorator
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
class EnvelopeDecorator extends Decorator
{

	/** @var IResponse */
	private $response;

	/**
	 * @param IDataResource $resource
	 * @param IResponse $response
	 */
	public function __construct(IDataResource $resource, IResponse $response)
	{
		parent::__construct($resource);
		$this->response = $response;
	}

	/**
	 * Get enveloped resource data
	 * @return array
	 */
	public function getData()
	{
		$data = parent::getData();
		return array(
			'status_code' => $this->response->getCode(),
			'headers' => $this->response->getHeaders(),
			'response' => $data
		);
	}

}