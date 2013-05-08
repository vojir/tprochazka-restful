<?php
namespace Drahak\Restful;

/**
 * REST client request Input interface
 * @package Drahak\Restful
 * @author Drahomír Hanák
 */
interface IInput
{

	/**
	 * Get parsed input data
	 * @return mixed
	 */
	public function getData();

	/**
	 * Get input method name
	 * @return string
	 */
	public function getMethod();

	/**
	 * Set input mapper
	 * @param IMapper $mapper
	 * @return IInput
	 */
	public function setMapper(IMapper $mapper);

}