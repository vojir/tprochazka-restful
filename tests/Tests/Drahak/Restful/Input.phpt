<?php
namespace Tests\Drahak\Restful;

require_once __DIR__ . '/../../bootstrap.php';

use Drahak\Restful\Input;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Input.
 *
 * @testCase Tests\Drahak\Restful\InputTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful
 */
class InputTest extends TestCase
{

	/** @var MockInterface */
	private $request;

	/** @var MockInterface */
	private $mapperContext;

	/** @var MockInterface */
	private $validation;

	/** @var Input */
	private $input;

    protected function setUp()
    {
		parent::setUp();
		$this->request = $this->mockista->create('Nette\Http\IRequest');
		$this->validation = $this->mockista->create('Drahak\Restful\Validation\ValidationSchema');
		$this->mapperContext = $this->mockista->create('Drahak\Restful\Mapping\MapperContext');
		$this->request->expects('getHeader')
			->once()
			->with('Content-Type')
			->andReturn('application/json');
		$this->mapperContext->expects('getMapper')
			->with('application/json')
			->andReturn(NULL);

		$this->input = new Input($this->request, $this->mapperContext, $this->validation);
    }
    
    public function testObtainDataFromPost()
    {
		$data = array('message' => 'Hello world');
		$this->request->expects('getPost')
			->once()
			->andReturn($data);

		$this->request->expects('getQuery')->never();

		$result = $this->input->getData();
		Assert::same($data, $result);
    }

	public function testObtainDataFromQueryString()
	{
		$data = array('message' => 'Hello world');
		$this->request->expects('getQuery')
			->once()
			->andReturn($data);

		$this->request->expects('getPost')->never();

		$result = $this->input->getData();
		Assert::same($data, $result);
	}

}
\run(new InputTest());