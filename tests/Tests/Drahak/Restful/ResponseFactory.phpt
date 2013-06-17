<?php
namespace Tests\Drahak\Restful;

require_once __DIR__ . '/../../bootstrap.php';

use Drahak\Restful\Application\Responses\JsonpResponse;
use Drahak\Restful\Application\Responses\JsonResponse;
use Drahak\Restful\Http\IRequest;
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
		$url = new Nette\Http\Url('http://localhost/test');
		$paginator = $this->createPaginatorMock($url);

		$this->response->expects('setHeader')->atLeastOnce();
		$this->request->expects('isJsonp')
			->once()
			->andReturn(FALSE);
		$this->request->expects('getUrl')
			->once()
			->andReturn($url);
		$this->request->expects('getMethod')->once()->andReturn(IRequest::GET);

		$this->response->expects('setCode')
			->once()
			->with($code);
		$this->filter->expects('getPaginator')
			->once()
			->andReturn($paginator);
	}

	/**
	 * Create paginator mock
	 * @param Nette\Http\Url $url
	 * @return MockInterface
	 */
	private function createPaginatorMock(Nette\Http\Url $url)
	{
		$paginator = $this->mockista->create('Nette\Utils\Paginator');
		$paginator->expects('setUrl')->once()->with($url);
		$paginator->expects('getPage')->atLeastOnce()->andReturn(1);
		$paginator->expects('getLastPage')->atLeastOnce()->andReturn(9);
		$paginator->expects('getItemsPerPage')->atLeastOnce()->andReturn(10);
		$paginator->expects('getOffset')->atLeastOnce()->andReturn(10);
		$paginator->expects('getItemCount')->atLeastOnce()->andReturn(100);
		$paginator->expects('setPage')->atLeastOnce();
		return $paginator;
	}

}
\run(new ResponseFactoryTest());