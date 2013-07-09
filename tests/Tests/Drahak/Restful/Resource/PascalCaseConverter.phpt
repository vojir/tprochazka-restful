<?php
namespace Tests\Drahak\Restful\Resource;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Resource\PascalCaseConverter;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Resource\PascalCaseConverter.
 *
 * @testCase Tests\Drahak\Restful\Resource\PascalCaseConverterTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Resource
 */
class PascalCaseConverterTest extends TestCase
{

	/** @var PascalCaseConverter */
	private $converter;
    
    protected function setUp()
    {
		parent::setUp();
		$this->converter = new PascalCaseConverter();
    }
    
    public function testConvertsArrayKeysToCamelCase()
    {
		$data = array(
			'nice array-key' => 'value'
		);

		$result = $this->converter->convert($data);
		$keys = array_keys($result);
		Assert::same('NiceArrayKey', $keys[0]);
    }

}
\run(new PascalCaseConverterTest());