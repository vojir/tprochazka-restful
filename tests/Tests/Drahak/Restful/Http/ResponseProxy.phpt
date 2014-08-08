<?php
namespace Tests\Drahak\Restful\Http;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Http\ResponseProxy;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Http\ResponseProxy.
 *
 * @testCase Tests\Drahak\Restful\Http\ResponseProxyTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Http
 */
class ResponseProxyTest extends TestCase
{

	/** @var MockInterface */
	private $response;

	/** @var Input */
	private $responseProxy;

    public function setUp()
    {
		parent::setUp();
		$this->response = $this->mockista->create('Nette\Http\IResponse');
		$this->responseReflection = $this->mockista->create();
		$this->responseProxy = new ResponseProxy($this->response);
    }

    public function testSetsResponseCodeThroughOriginalResponseClassMethod()
    {
    	$this->response->expects('setCode')
    		->once()
    		->with(200);

    	$this->responseProxy->setCode(200);
    }
    
    public function testThrowsExceptionIfResponseCodeIsInvalid()
    {
    	$invalidCode = new \Nette\InvalidArgumentException();
    	$this->response->expects('setCode')
    		->once()
    		->with(5)
    		->andThrow($invalidCode);

    	Assert::exception(function() {
    		$this->responseProxy->setCode(5);	
    	}, 'Nette\InvalidArgumentException');
    }

    public function testCallsOriginalResponseClassMethods()
    {
    	$this->response->expects('getReflection')
    		->once()
    		->andReturn($this->responseReflection);
    	$this->responseReflection->expects('hasMethod')
    		->once()
    		->with('date')
    		->andReturn(TRUE);

    	$this->response->expects('date')
    		->once()
    		->with(1, 2, 3);

    	$this->responseProxy->date(1, 2, 3);
    }

}
\run(new ResponseProxyTest());
