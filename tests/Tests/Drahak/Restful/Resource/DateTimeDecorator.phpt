<?php
namespace Tests\Drahak\Restful\Resource;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Resource\DateTimeDecorator;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Resource\DateTimeDecorator.
 *
 * @testCase Tests\Drahak\Restful\Resource\DateTimeDecoratorTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Resource
 */
class DateTimeDecoratorTest extends TestCase
{

	/** @var MockInterface */
	private $resource;

	/** @var DateTimeDecorator */
	private $decorator;

    protected function setUp()
    {
		parent::setUp();
		$this->resource = $this->mockista->create('Drahak\Restful\IResource');
		$this->decorator = new DateTimeDecorator($this->resource);
    }
    
    public function testGetDecoratedData()
    {
		$data = array(
			'date' => new \DateTime('19.1.1996')
		);
		$this->resource->expects('getData')
			->once()
			->andReturn($data);

		$data = $this->decorator->getData();
		Assert::equal($data['date'], '1996-01-19T00:00:00+01:00');
    }

}
\run(new DateTimeDecoratorTest());