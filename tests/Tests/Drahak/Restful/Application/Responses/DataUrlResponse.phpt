<?php
namespace Tests\Drahak\Restful\Application\Responses;

require_once __DIR__ . '/../../../../bootstrap.php';

use Drahak\Restful\Application\Responses\DataUrlResponse;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Application\Responses\DataUrlResponse.
 *
 * @testCase Tests\Drahak\Restful\Application\Responses\DataUrlResponseTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Application\Responses
 */
class DataUrlResponseTest extends TestCase
{

	/** @var MockInterface */
	private $mapper;

	/** @var MockInterface */
	private $request;

	/** @var DataUrlResponse */
	private $response;
    
    protected function setUp()
    {
		parent::setUp();
		$this->mapper = $this->mockista->create('Drahak\Restful\Mapping\DataUrlMapper');
		$this->request = $this->mockista->create('Nette\Http\IRequest');
		$this->response = new DataUrlResponse(array(
			'src' => 'Hello world',
			'type' => 'text/plain'
		));
		$this->response->setMapper($this->mapper);
    }
    
    public function testResponseWithValidDataUrl()
    {
		$url = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==';
		$data = array('src' => 'Hello world', 'type' => 'text/plain');
		$this->mapper->expects('parseResponse')
			->once()
			->with($data)
			->andReturn($url);

		$response = $this->mockista->create('Nette\Http\IResponse');
		$response->expects('setContentType')
			->once()
			->with('text/plain');

		ob_start();
		$this->response->send($this->request, $response);
		$dataUrl = ob_get_contents();
		ob_end_clean();
		Assert::same($dataUrl, $url);
    }

}
\run(new DataUrlResponseTest());