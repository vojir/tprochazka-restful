<?php
namespace Tests\Drahak\Restful\Resource;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Resource\Decorator;
use Drahak\Restful\Resource\EnvelopeDecorator;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Resource\EnvelopeDecorator.
 *
 * @testCase Tests\Drahak\Restful\Resource\EnvelopeDecoratorTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Resource
 */
class EnvelopeDecoratorTest extends TestCase
{

	/** @var MockInterface */
	private $response;

	/** @var MockInterface */
	private $resource;

	/** @var Decorator */
	private $decorator;
    
    protected function setUp()
    {
		parent::setUp();
		$this->resource = $this->mockista->create('Drahak\Restful\IResource');
		$this->response = $this->mockista->create('Nette\Http\IResponse');
		$this->decorator = new EnvelopeDecorator($this->resource, $this->response);
    }
    
    public function testEnvelopResourceDataAndAppendAditionInformations()
    {
		$headers = array('X-Testing' => true);
		$data = array('responseKey' => 'testValue');

		$this->resource->expects('getData')
			->once()
			->andReturn($data);
		$this->response->expects('getHeaders')
			->once()
			->andReturn($headers);
		$this->response->expects('getCode')
			->once()
			->andReturn(200);

		$data = $this->decorator->getData();
    	Assert::equal($data['response']['responseKey'], 'testValue');
		Assert::equal($data['status_code'], 200);
		Assert::same($data['headers'], $headers);
	}

}
\run(new EnvelopeDecoratorTest());