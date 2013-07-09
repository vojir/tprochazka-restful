<?php
namespace Tests\Drahak\Restful\Http;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Http\IInput;
use Drahak\Restful\Http\InputFactory;
use Drahak\Restful\InvalidStateException;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Http\InputFactory.
 *
 * @testCase Tests\Drahak\Restful\Http\InputFactoryTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Http
 */
class InputFactoryTest extends TestCase
{

	/** @var MockInterface */
	private $request;

	/** @var MockInterface */
	private $mapperContext;

	/** @var MockInterface */
	private $validationScopeFactory;

	/** @var InputFactory */
	private $inputFactory;

    public function setUp()
    {
		parent::setUp();
		$this->request = $this->mockista->create('Drahak\Restful\Http\IRequest');
		$this->mapperContext = $this->mockista->create('Drahak\Restful\Mapping\MapperContext');
		$this->validationScopeFactory = $this->mockista->create('Drahak\Restful\Validation\ValidationScopeFactory');
		$this->inputFactory = new InputFactory($this->request, $this->mapperContext, $this->validationScopeFactory);
    }
    
    public function testCreateInputFromPostData()
    {
		$data = array('post' => 'data');
		$exception = new InvalidStateException;

		$this->request->expects('getPost')
			->atLeastOnce()
			->andReturn($data);
		$this->request->expects('getHeader')
			->atLeastOnce()
			->with('Content-Type')
			->andReturn('application/test');

		$this->mapperContext->expects('getMapper')
			->once()
			->with('application/test')
			->andThrow($exception);

		$input = $this->inputFactory->create();
		Assert::true($input instanceof IInput);
		Assert::same($input->getData(), $data);
    }

	public function testCreateInputFromGetUrlQueryParameters()
	{
		$data = array('get' => 'data');
		$exception = new InvalidStateException;

		$this->request->expects('getPost')
			->once()
			->andReturn(array());
		$this->request->expects('getQuery')
			->atLeastOnce()
			->andReturn($data);
		$this->request->expects('getHeader')
			->atLeastOnce()
			->with('Content-Type')
			->andReturn('application/test');

		$this->mapperContext->expects('getMapper')
			->once()
			->with('application/test')
			->andThrow($exception);

		$input = $this->inputFactory->create();
		Assert::true($input instanceof IInput);
		Assert::same($input->getData(), $data);
	}
    
}
\run(new InputFactoryTest());