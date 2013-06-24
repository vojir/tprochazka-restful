<?php
namespace Tests\Drahak\Restful\Tools\Documentation;

require_once __DIR__ . '/../../../../bootstrap.php';

use Drahak\Restful\Tools\Documentation\ServiceSpy;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Tools\Documentation\ServiceSpy.
 *
 * @testCase Tests\Drahak\Restful\Tools\Documentation\ServiceSpyTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Tools\Documentation
 */
class ServiceSpyTest extends TestCase
{

	/** @var MockInterface */
	private $container;

	/** @var ServiceSpy */
	private $serviceSpy;

    protected function setUp()
    {
		parent::setUp();
		$this->container = $this->mockista->create('Nette\DI\Container');
		$this->serviceSpy = new ServiceSpy($this->container);
    }
    
    public function testApplySpyClassOnService()
    {
		$service = new \stdClass;
		$args = array('hello', 'world');

		$this->container->expects('findByType')
			->once()
			->with('Nette\Object')
			->andReturn(array('nette.object'));

		$this->container->expects('removeService')
			->once()
			->with('nette.object');

		$this->container->expects('createInstance')
			->once()
			->with('stdClass', $args)
			->andReturn($service);

		$this->container->expects('addService')
			->once()
			->with('nette.object', $service);

		$result = $this->serviceSpy->on('Nette\Object', 'stdClass', $args);
		Assert::same($result, $service);
    }

}
\run(new ServiceSpyTest());