<?php
namespace Tests\Drahak\Restful\Resource;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Resource\CamelCaseDecorator;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Resource\CamelCaseDecorator.
 *
 * @testCase Tests\Drahak\Restful\Resource\CamelCaseDecoratorTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Resource
 */
class CamelCaseDecoratorTest extends TestCase
{

	/** @var MockInterface */
	private $resource;

	/** @var CamelCaseDecorator */
	private $decorator;
    
    protected function setUp()
    {
		parent::setUp();
		$this->resource = $this->mockista->create('Drahak\Restful\IResource');
		$this->decorator = new CamelCaseDecorator($this->resource);
    }
    
    public function testConvertsSnakeCaseToCamelCase()
    {
		$data = array(
			'nice array-key' => 'value'
		);

		$this->resource->expects('getData')
			->once()
			->andReturn($data);

		$result = $this->decorator->getData();
		$keys = array_keys($result);
		Assert::same('niceArrayKey', $keys[0]);
    }

}
\run(new CamelCaseDecoratorTest());