<?php
namespace Drahak\Restful\Application;

use Nette\Application\Application;

/**
 * IApplicationEvent
 * @package Drahak\Restful\Application
 * @author Drahomír Hanák
 */
interface IApplicationEvent
{

	/**
	 * On application run
	 * @param Application $application
	 * @return void
	 */
	public function run(Application $application);

}