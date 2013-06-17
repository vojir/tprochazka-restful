<?php
namespace Tests\Drahak\Restful;

require_once __DIR__ . '/../../bootstrap.php';

use Drahak\Restful\Application\Responses\JsonpResponse;
use Drahak\Restful\Application\Responses\JsonResponse;
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

	/** @var MockInterface */
	private $filter;

    protected function setUp()
    {
		parent::setUp();
		$this->response = $this->mockista->create('Nette\Http\IResponse');
		$this->request = $this->mockista->create('Drahak\Restful\Http\IRequest');
		$this->filter = $this->mockista->create('Drahak\Restful\Utils\RequestFilter');
		$this->factory = new ResponseFactory($this->response, $this->request, $this->filter);
		$this->resource = $this->mockista->create('Drahak\Restful\Resource');
	}

	public function testCreateResponse()
	{
		$this->mockResponseFactory(204);
		$this->resource->expects('getContentType')
			->once()
			->andReturn(IResource::JSON);
		$this->resource->expects('getData')
			->once()
			->andReturn(array());

		$response = $this->factory->create($this->resource);
		Assert::true($response instanceof JsonResponse);
	}

	public function testCreateCustomResponse()
	{
		$this->mockResponseFactory();
		$this->resource->expects('getContentType')
			->once()
			->andReturn('text');
		$this->resource->expects('getData')
			->once()
			->andReturn('test');

		$this->factory->registerResponse('text', 'Nette\Application\Responses\TextResponse');
		$response = $this->factory->create($this->resource);

		Assert::true($response instanceof Nette\Application\Responses\TextResponse);
	}

	public function testCreateJsonpResponseWhenJsonpIsActive()
	{
		$this->mockResponseFactory();
		$this->resource->expects('getContentType')
			->once()
			->andReturn('text');
		$this->resource->expects('getData')
			->once()
			->andReturn('test');
		$this->request->expects('isJsonp')
			->once()
			->andReturn(TRUE);

		$this->factory->registerResponse('text', 'Nette\Application\Responses\TextResponse');
		$response = $this->factory->create($this->resource);

		Assert::true($response instanceof JsonpResponse);
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

	private function mockResponseFactory($code = 200)
	{
		$url = 'http://localhost/test';

		$paginator = $this->mockista->create('Drahak\Restful\Utils\Paginator');
		$paginator->expects('setUrl')->once()->with($url);
		$paginator->expects('getNextPageUrl')->once()->andReturn($url . '?offset=10&limit=10');
		$paginator->expects('getLastPageUrl')->once()->andReturn($url . '?offset=90&limit=10');
		$paginator->expects('getItemCount')->once()->andReturn(100);

		$this->response->expects('setHeader')->atLeastOnce();
		$this->request->expects('isJsonp')
			->once()
			->andReturn(FALSE);
		$this->request->expects('getUrl')
			->once()
			->andReturn($url);

		$this->response->expects('setCode')
			->once()
			->with($code);
		$this->filter->expects('getPaginator')
			->once()
			->andReturn($paginator);
	}

}
\run(new ResponseFactoryTest());