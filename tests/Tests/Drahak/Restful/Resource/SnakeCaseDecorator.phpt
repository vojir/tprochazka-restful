<?php
namespace Tests\Drahak\Restful\Resource;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Resource\SnakeCaseDecorator;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Resource\SnakeCaseDecorator.
 *
 * @testCase Tests\Drahak\Restful\Resource\SnakeCaseDecoratorTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Resource
 */
class SnakeCaseDecoratorTest extends TestCase
{

	/** @var MockInterface */
	private $resource;

	/** @var SnakeCaseDecorator */
	private $decorator;

    protected function setUp()
    {
		parent::setUp();
		$this->resource = $this->mockista->create('Drahak\Restful\IResource');
		$this->decorator = new SnakeCaseDecorator($this->resource);
    }
    
    public function testConvertsCamelCaseToSnakeCase()
    {
		$data = array(
			'camelCase' => 'is not so good to read'
		);

		$this->resource->expects('getData')
			->once()
			->andReturn($data);

		$result = $this->decorator->getData();
		$keys = array_keys($result);
		Assert::same('camel_case', $keys[0]);
    }


}
\run(new SnakeCaseDecoratorTest());