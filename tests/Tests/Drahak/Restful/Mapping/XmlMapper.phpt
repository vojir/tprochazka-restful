<?php
namespace Tests\Drahak\Restful\Mapping;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Mapping\XmlMapper;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Mapping\XmlMapper.
 *
 * @testCase Tests\Drahak\Restful\Mapping\XmlMapperTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Mapping
 */
class XmlMapperTest extends TestCase
{

	/** @var XmlMapper */
	private $mapper;

    protected function setUp()
    {
		parent::setUp();
		$this->mapper = new XmlMapper('root');
    }
    
    public function testConvertDataArrayToXml()
    {
		$xml = $this->mapper->parseResponse(array('node' => 'value'));
		$dom = Tester\DomQuery::fromXml($xml);
		Assert::true($dom->has('root'));
		Assert::true($dom->has('root node'));
	}

	public function testConvertXmlToDataArray()
	{
		$array = $this->mapper->parseRequest('<?xml version="1.0" encoding="utf-8" ?><root><node>value</node></root>');
		Assert::equal($array['node'], 'value');
	}

}
\run(new XmlMapperTest());