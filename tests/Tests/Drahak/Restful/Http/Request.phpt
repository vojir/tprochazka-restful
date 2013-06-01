<?php
namespace Tests\Drahak\Restful\Http;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Http\Request;
use Mockista\MockInterface;
use Nette;
use Nette\Utils\Strings;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Http\Request.
 *
 * @testCase Tests\Drahak\Restful\Http\RequestTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Http
 */
class RequestTest extends TestCase
{

	/** @var MockInterface */
	private $urlScript;

	/** @var Request */
	private $request;

    protected function setUp()
    {
		parent::setUp();
		$this->request = $this->createRequest();
    }
    
    public function testDoesNotOverrideRequestMethod()
    {
		$method = $this->request->getMethod();
		Assert::equal($method, Nette\Http\IRequest::GET);
    }

	public function testOverrideRequestMethodWithQueryParameter()
	{
		$this->request = $this->createRequest(array(
			Request::METHOD_OVERRIDE_PARAM => Nette\Http\IRequest::PUT
		), array(
			Strings::lower(Request::METHOD_OVERRIDE_HEADER) => Nette\Http\IRequest::DELETE
		));

		$method = $this->request->getMethod();
		Assert::equal($method, Nette\Http\IRequest::PUT);
	}

	public function testOverrideRequestMethodWithHeader()
	{
		$this->request = $this->createRequest(NULL, array(
			Strings::lower(Request::METHOD_OVERRIDE_HEADER) => Nette\Http\IRequest::DELETE
		));

		$method = $this->request->getMethod();
		Assert::equal($method, Nette\Http\IRequest::DELETE);
	}

	public function testGetOriginalMethod()
	{
		$this->request = $this->createRequest(array(
			Request::METHOD_OVERRIDE_PARAM => Nette\Http\IRequest::PUT
		), array(
			Strings::lower(Request::METHOD_OVERRIDE_HEADER) => Nette\Http\IRequest::DELETE
		));

		$method = $this->request->getOriginalMethod();
		Assert::equal($method, Nette\Http\IRequest::GET);
	}

	/**
	 * @param array|null $query
	 * @param array|null $headers
	 * @return Request
	 */
	private function createRequest($query = NULL, $headers = NULL)
	{
		$this->urlScript = $this->mockista->create('Nette\Http\UrlScript');
		$this->urlScript->expects('freeze');
		$this->urlScript->expects('__get');
		return new Request($this->urlScript, $query, NULL, NULL, NULL, $headers, Nette\Http\IRequest::GET);
	}
}
\run(new RequestTest());