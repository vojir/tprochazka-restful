<?php
namespace Tests\Drahak\Api\Application\Routes;

require_once __DIR__ . '/../../../../bootstrap.php';

use Drahak\Api\Application\Routes\CrudRoute;
use Drahak\Api\IResourceRouter;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Api\Application\Routes\CrudRoute.
 *
 * @testCase Tests\Drahak\Api\Application\Routes\CrudRouteTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Api\Application\Routes
 */
class CrudRouteTest extends TestCase
{

	/** @var CrudRoute */
	private $route;

    protected function setUp()
    {
		parent::setUp();
		$this->route = new CrudRoute('resources/crud', 'Crud');
    }
    
    public function testPredefinedCrudActionDictionary()
    {
		$array = $this->route->actionDictionary;
		Assert::equal($array[IResourceRouter::GET], 'read');
		Assert::equal($array[IResourceRouter::PUT], 'create');
		Assert::equal($array[IResourceRouter::POST], 'update');
		Assert::equal($array[IResourceRouter::DELETE], 'delete');
    }

}
\run(new CrudRouteTest());