<?php
namespace Tests\Drahak\Restful\Validation;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Validation\IField;
use Drahak\Restful\Validation\IValidator;
use Drahak\Restful\Validation\ValidationException;
use Drahak\Restful\Validation\ValidationSchema;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Validation\ValidationSchema.
 *
 * @testCase Tests\Drahak\Restful\Validation\ValidationSchemaTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Validation
 */
class ValidationSchemaTest extends TestCase
{

	/** @var MockInterface */
	private $validator;

	/** @var ValidationSchema */
	private $schema;

    protected function setUp()
    {
		parent::setUp();
		$this->validator = $this->mockista->create('Drahak\Restful\Validation\Validator');
		$this->schema = new ValidationSchema($this->validator);
    }
    
    public function testCreateField()
    {
		$field = $this->schema->field('test');
		Assert::true($field instanceof IField);
		Assert::equal($field->getName(), 'test');
		Assert::equal($field->getValidator(), $this->validator);
    }

	public function testValidateArrayData()
	{
		$exception = new ValidationException('text');

		$rule = $this->schema->field('test');
		$rule->addRule(IValidator::INTEGER, 'Please add integer');

		$this->validator->expects('validate')
			->once()
			->andThrow($exception);

		$errors = $this->schema->validate(array('test' => 'Hello world'));
		Assert::equal($errors[0]['field'], 'test');
		Assert::equal($errors[0]['message'], 'Please add integer');
	}

}
\run(new ValidationSchemaTest());