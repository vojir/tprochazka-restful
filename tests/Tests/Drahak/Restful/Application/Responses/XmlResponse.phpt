<?php
namespace Tests\Drahak\Restful\Application\Responses;

require_once __DIR__ . '/../../../../bootstrap.php';

use Drahak\Restful\Application\Responses\XmlResponse;
use Drahak\Restful\IResource;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Application\Responses\XmlResponse.
 *
 * @testCase Tests\Drahak\Restful\Application\Responses\XmlResponseTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Application\Responses
 */
class XmlResponseTest extends TestCase
{

	/** @var MockInterface */
	private $mapper;

	/** @var XmlResponse */
	private $response;

    protected function setUp()
    {
		parent::setUp();
		$this->mapper = $this->mockista->create('Drahak\Restful\Mapping\XmlMapper');
		$this->response = new XmlResponse(array());
		$this->response->setMapper($this->mapper);
    }
    
    public function testSendXmlResponse()
    {
		$this->mapper->expects('parseResponse')
			->once()
			->andReturn('Some XML');

		$httpRequest = $this->mockista->create('Nette\Http\IRequest');
		$httpResponse = $this->mockista->create('Nette\Http\IResponse');

		$httpResponse->expects('setContentType')
			->once()
			->with(IResource::XML);


		ob_start();
		$this->response->send($httpRequest, $httpResponse);
		$result = ob_get_contents();
		ob_end_clean();

		Assert::equal($result, 'Some XML');
	}
}
\run(new XmlResponseTest());