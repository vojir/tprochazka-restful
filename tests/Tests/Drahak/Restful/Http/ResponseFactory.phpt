<?php
namespace Tests\Drahak\Restful\Http;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Http\ResponseFactory;
use Drahak\Restful\Http\ResponseProxy;
use Drahak\Restful\InvalidStateException;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Http\ResponseFactory.
 *
 * @testCase Tests\Drahak\Restful\Http\ResponseFactoryTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Http
 */
class ResponseFactoryTest extends TestCase
{

	/** @var ResponseFactory */
	private $factory;

	/** @var MockInterface */
	private $request;

	/** @var MockInterface */
	private $response;

	/** @var MockInterface */
	private $filter;

    public function setUp()
    {
		parent::setUp();
		$this->request = $this->mockista->create('Drahak\Restful\Http\IRequest');
		$this->filter = $this->mockista->create('Drahak\Restful\Utils\RequestFilter');
		$this->response = $this->mockista->create('Nette\Http\IResponse');
		$this->factory = new ResponseFactory($this->request, $this->filter);
		$this->factory->setResponse($this->response);
    }
    
    public function testCreateHttpResponseWithGivenStatusCode()
    {
		$exception = new InvalidStateException;
		$this->filter->expects('getPaginator')
			->once()
			->andThrow($exception);

		$this->response->expects('setCode')
			->once()
			->with(422);

		$response = $this->factory->createHttpResponse(422);
		Assert::true($response instanceof ResponseProxy);
		Assert::equal($response->getCode(), 422);
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
