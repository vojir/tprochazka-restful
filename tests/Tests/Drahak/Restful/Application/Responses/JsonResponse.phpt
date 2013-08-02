<?php
namespace Tests\Drahak\Restful\Application\Responses;

require_once __DIR__ . '/../../../../bootstrap.php';

use Drahak\Restful\Application\Responses\JsonResponse;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Application\Responses\JsonResponse.
 *
 * @testCase Tests\Drahak\Restful\Application\Responses\JsonResponseTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Application\Responses
 */
class JsonResponseTest extends TestCase
{

	/** @var MockInterface */
	private $mapper;

	/** @var JsonResponse */
	private $response;

    protected function setUp()
    {
		parent::setUp();
		$this->mapper = $this->mockista->create('Drahak\Restful\Mapping\IMapper');
		$this->response = new JsonResponse(array('hello' => 'world'), $this->mapper);
    }
    
    public function testResponseWithJson()
    {
		$output = '{"hello":"world"}';

		$this->mapper->expects('stringify')
			->once()
			->with(array('hello' => 'world'), FALSE)
			->andReturn($output);

		$httpRequest = $this->mockista->create('Drahak\Restful\Http\IRequest');
		$httpResponse = $this->mockista->create('Nette\Http\IResponse');

		$httpResponse->expects('setContentType')
			->once()
			->with('application/json', 'UTF-8');

		$httpRequest->expects('isPrettyPrint')
			->once()
			->andReturn(FALSE);

		ob_start();
		$this->response->send($httpRequest, $httpResponse);
		$content = ob_get_clean();

		Assert::same($content, $output);
    }

}
\run(new JsonResponseTest());
