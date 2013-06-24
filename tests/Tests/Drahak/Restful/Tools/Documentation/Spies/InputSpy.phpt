<?php
namespace Tests\Drahak\Restful\Tools\Documentation\Spies;

require_once __DIR__ . '/../../../../../bootstrap.php';

use Drahak\Restful\Tools\Documentation\Spies\InputSpy;
use Drahak\Restful\Validation\IValidator;
use Drahak\Restful\Validation\Rule;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Tools\Documentation\Spies\InputSpy.
 *
 * @testCase Tests\Drahak\Restful\Tools\Documentation\Spies\InputSpyTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Tools\Documentation\Spies
 */
class InputSpyTest extends TestCase
{

	/** @var MockInterface */
	private $request;

	/** @var MockInterface */
	private $mapperContext;

	/** @var MockInterface */
	private $validationSchema;

	/** @var InputSpy */
	private $spy;

    protected function setUp()
    {
		parent::setUp();
		$this->request = $this->mockista->create('Drahak\Restful\Http\IRequest');
		$this->mapperContext = $this->mockista->create('Drahak\Restful\Mapping\MapperContext');
		$this->validationSchema = $this->mockista->create('Drahak\Restful\Validation\IValidationSchema');

		$this->request->expects('getHeader');
		$this->mapperContext->expects('getMapper');
		$this->spy = new InputSpy($this->request, $this->mapperContext, $this->validationSchema);
    }
    
    public function testAddAccessedFieldByGetter()
    {
		$this->spy->setData(array('some' => 'test'));
		$this->spy->some;
		Assert::equal($this->spy->getAccessedFields(), array('some'));
    }

	public function testAddAccessedFiledByFieldMethod()
	{
		$this->validationSchema->expects('field')
			->once()
			->with('some');

		$this->spy->field('some');
		Assert::equal($this->spy->getAccessedFields(), array('some'));
	}

	public function testGetParameter()
	{
		$field = $this->mockista->create('Drahak\Restful\Validation\IField');
		$rule = new Rule;
		$rule->expression = IValidator::INTEGER;

		$field->expects('getRules')
			->once()
			->andReturn(array($rule));

		$this->validationSchema->expects('field')
			->once()
			->with('some')
			->andReturn($field);

		$result = $this->spy->getParameter('some');
		Assert::equal($result['name'], 'some');
		Assert::equal($result['description'], 'Value must be an integer');
		Assert::equal($result['type'], 'integer');
		Assert::false($result['optional']);
	}

}
\run(new InputSpyTest());