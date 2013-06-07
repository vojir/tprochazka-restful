<?php
namespace Tests\Drahak\Restful\Application\Responses;

require_once __DIR__ . '/../../../../bootstrap.php';

use Drahak\Restful\Application\Responses\JsonpResponse;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Application\Responses\JsonpResponse.
 *
 * @testCase Tests\Drahak\Restful\Application\Responses\JsonpResponseTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Application\Responses
 */
class JsonpResponseTest extends TestCase
{

	/** @var MockInterface */
	private $httpRequest;

	/** @var MockInterface */
	private $httpResponse;

	/** @var JsonpResponse */
	private $response;
    
    protected function setUp()
    {
		parent::setUp();
		$this->httpRequest = $this->mockista->create('Nette\Http\IRequest');
		$this->httpResponse = $this->mockista->create('Nette\Http\IResponse');
		$this->response = new JsonpResponse(array('test' => 'JSONP'));
    }
    
    public function testResponseWithJSONP()
    {
		$this->httpResponse->expects('setContentType')
			->once()
			->with('application/javascript');
		$this->httpRequest->expects('getQuery')
			->once()
			->with('envelope')
			->andReturn('callbackFn');

		ob_start();
		$this->response->send($this->httpRequest, $this->httpResponse);
		$content = ob_get_contents();
		ob_end_flush();

		Assert::same($content, 'callbackFn({"test":"JSONP"});');
    }

}
\run(new JsonpResponseTest());