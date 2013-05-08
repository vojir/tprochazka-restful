<?php
namespace Drahak\Restful;

/**
 * Resource data mapper interface
 * @package Drahak\Restful\Mapping
 * @author Drahomír Hanák
 */
interface IMapper
{

	/**
	 * Convert array or Traversable input to string output response
	 * @param array|\Traversable $data
	 * @return mixed
	 *
	 * @throws InvalidArgumentException
	 */
	public function parseResponse($data);

	/**
	 * Convert client request data to array or traversable
	 * @param mixed $data
	 * @return array|\Traversable
	 *
	 * @throws InvalidArgumentException
	 */
	public function parseRequest($data);

}