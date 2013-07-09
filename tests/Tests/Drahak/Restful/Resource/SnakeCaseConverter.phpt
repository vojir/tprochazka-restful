<?php
namespace Tests\Drahak\Restful\Resource;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Resource\SnakeCaseConverter;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Resource\SnakeCaseConverter.
 *
 * @testCase Tests\Drahak\Restful\Resource\SnakeCaseConverterTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Resource
 */
class SnakeCaseConverterTest extends TestCase
{

	/** @var SnakeCaseConverter */
	private $converter;

    protected function setUp()
    {
		parent::setUp();
		$this->converter = new SnakeCaseConverter();
    }
    
    public function testConvertsCamelCaseToSnakeCase()
    {
		$data = array(
			'camelCase' => 'is not so good to read'
		);

		$result = $this->converter->convert($data);
		$keys = array_keys($result);
		Assert::same('camel_case', $keys[0]);
    }


}
\run(new SnakeCaseConverterTest());