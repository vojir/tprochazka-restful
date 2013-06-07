<?php
namespace Tests\Drahak\Restful;

require_once __DIR__ . '/../../bootstrap.php';

use Drahak\Restful\IResource;
use Drahak\Restful\ResponseFactory;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\ResponseFactory.
 *
 * @testCase Tests\Drahak\Restful\ResponseFactoryTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful
 */
class ResponseFactoryTest extends TestCase
{
	/** @var ResponseFactory */
	private $factory;

	/** @var MockInterface */
	private $resource;

	/** @var MockInterface */
	private $request;

	/** @var MockInterface */
	private $response;

    protected function setUp()
    {
		parent::setUp();
		$this->response = $this->mockista->create('Nette\Http\IResponse');
		$this->request = $this->mockista->create('Drahak\Restful\Http\IRequest');
		$this->factory = new ResponseFactory($this->response, $this->request);
		$this->resource = $this->mockista->create('Drahak\Restful\Resource');
	}

	public function testCreateResponse()
	{
		$this->resource->expects('getContentType')
			->once()
			->andReturn(IResource::JSON);
		$this->resource->expects('getData')
			->once()
			->andReturn(array());
		$this->request->expects('getQuery');

		$response = $this->factory->create($this->resource);
		Assert::true($response instanceof Nette\Application\Responses\JsonResponse);
	}

	public function testCreateCustomResponse()
	{
		$this->resource->expects('getContentType')
			->once()
			->andReturn('text');
		$this->resource->expects('getData')
			->once()
			->andReturn('test');
		$this->request->expects('getQuery');

		$this->factory->registerResponse('text', 'Nette\Application\Responses\TextResponse');
		$response = $this->factory->create($this->resource);

		Assert::true($response instanceof Nette\Application\Responses\TextResponse);
	}

	public function testThrowsExceptionWhenResponseTypeIsNotFound()
	{
		$this->resource->expects('getContentType')
			->once()
			->andReturn('drahak/test');

		Assert::throws(function() {
			$this->factory->create($this->resource);
		}, 'Drahak\Restful\InvalidStateException');
	}

    public function testThrowsExceptionWhenResponseClassNotExists()
    {
		$factory = $this->factory;
		Assert::throws(function() use($factory) {
			$factory->registerResponse('test/plain', 'Drahak\TestResponse');
		}, 'Drahak\Restful\InvalidArgumentException');
    }
}
\run(new ResponseFactoryTest());