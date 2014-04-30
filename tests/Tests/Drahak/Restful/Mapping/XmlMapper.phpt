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
		$xml = $this->mapper->stringify(array('node' => 'value'));
		$dom = Tester\DomQuery::fromXml($xml);
		Assert::true($dom->has('root'));
		Assert::true($dom->has('root node'));
	}

	public function testConvertArrayListWithNumericIndexes()
	{
		$data = array('hello', 'world');
		$xml = $this->mapper->stringify($data);
		$dom = Tester\DomQuery::fromXml($xml);

		$items = $dom->find('root item');
		Assert::equal(count($items), 2);
		Assert::equal((string)$items[0], 'hello');
		Assert::equal((string)$items[1], 'world');
		Assert::true($items[0]->has('[index]'));
		Assert::true($items[1]->has('[index]'));
	}

	public function testSetCustomItemElementName()
	{
		$data = array('hello', 'world');
		$this->mapper->setRootElement('base');
		$this->mapper->setItemElement('test');
		$xml = $this->mapper->stringify($data);
		$dom = Tester\DomQuery::fromXml($xml);

		$items = $dom->find('base test');
		Assert::equal(count($items), 2);
		Assert::equal((string)$items[0], 'hello');
		Assert::equal((string)$items[1], 'world');
	}

	public function testConvertXmlToDataArray()
	{
		$array = $this->mapper->parse('<?xml version="1.0" encoding="utf-8" ?><root><node>value</node></root>');
		Assert::equal($array['node'], 'value');
	}

	public function testConvertsXmlRecursivelyToArray()
	{
		$array = $this->mapper->parse('<?xml version="1.0" encoding="UTF-8"?>
			<envelope>
			   <user>
			     <name>test</name>
			     <phone>500</phone>
			  </user>
			</envelope>');
		Assert::equal($array['user']['name'], 'test');
	}

	public function testThrowsMappingExceptionIfInvalidXMLisGiven()
	{
		Assert::throws(function() {
			$this->mapper->parse('<?xml version="1.0" encoding="UTF-8"?>
				<envelope>
				   <user>
				     <name>test
				     <phone>500</phone>
				  </user>
				</envelope>');
		}, 'Drahak\Restful\Mapping\MappingException');
	}

}
\run(new XmlMapperTest());
