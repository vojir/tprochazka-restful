<?php
namespace Drahak\Restful\Application;

use Nette\Application\Application;

/**
 * IErrorHandler
 * @package Drahak\Restful\Application
 * @author Drahomír Hanák
 */
interface IErrorHandler
{

	/**
	 * On application run
	 * @param Application $application
	 * @return void
	 */
	public function run(Application $application);

}