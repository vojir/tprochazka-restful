<?php
namespace Tests\Drahak\Restful\Resource;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Resource\DateTimeConverter;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Resource\DateTimeConverter.
 *
 * @testCase Tests\Drahak\Restful\Resource\DateTimeConverterTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Resource
 */
class DateTimeConverterTest extends TestCase
{

	/** @var DateTimeConverter */
	private $converter;

    protected function setUp()
    {
		parent::setUp();
		$this->converter = new DateTimeConverter('c');
    }
    
    public function testConvertDateTimeObjectsToString()
    {
		$data = array(
			array(
				'date' => new \DateTime('19.1.1996'),
				'modified' => new \DateTime('19.1.1996'),
			)
		);

		$data = $this->converter->convert($data);
		Assert::equal($data[0]['date'], '1996-01-19T00:00:00+01:00');
		Assert::equal($data[0]['modified'], '1996-01-19T00:00:00+01:00');
    }

}
\run(new DateTimeConverterTest());