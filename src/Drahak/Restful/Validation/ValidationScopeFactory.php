<?php
namespace Drahak\Restful\Validation;

/**
 * ValidationScopeFactory
 * @package Drahak\Restful\Validation
 * @author Drahomír Hanák
 */
interface ValidationScopeFactory
{

	/**
	 * Validation schema factory
	 * @return \Drahak\Restful\Validation\IValidationScope
	 */
	public function create();

}