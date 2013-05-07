<?php
namespace Tests\Drahak\Api\Mapping;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Api\Mapping\XmlMapper;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Api\Mapping\XmlMapper.
 *
 * @testCase Tests\Drahak\Api\Mapping\XmlMapperTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Api\Mapping
 */
class XmlMapperTest extends TestCase
{

	/** @var XmlMapper */
	private $mapper;

    protected function setUp()
    {
		parent::setUp();
		$this->mapper = new XmlMapper(array('node' => 'value'), 'root');
    }
    
    public function testConvertDataArrayToXml()
    {
		$xml = $this->mapper->convert();
		$dom = Tester\DomQuery::fromXml($xml);
		Assert::true($dom->has('root'));
		Assert::true($dom->has('root node'));
	}
}
\run(new XmlMapperTest());