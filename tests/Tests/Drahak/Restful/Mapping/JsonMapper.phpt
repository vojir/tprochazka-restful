<?php
namespace Tests\Drahak\Restful\Mapping;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\IMapper;
use Drahak\Restful\Mapping\JsonMapper;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Mapping\JsonMapper.
 *
 * @testCase Tests\Drahak\Restful\Mapping\JsonMapperTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Mapping
 */
class JsonMapperTest extends TestCase
{

	/** @var IMapper */
	private $mapper;
    
    protected function setUp()
    {
		parent::setUp();
		$this->mapper = new JsonMapper;
    }
    
    public function testConvertArrayToJson()
    {
		$array = array('node' => 'value');
		$json = $this->mapper->parseResponse($array);
		Assert::equal($json, '{"node":"value"}');
    }

	public function testConvertJsonToArray()
	{
		$array = $this->mapper->parseRequest('{"node":"value"}');
		Assert::equal($array['node'], 'value');
	}

	public function testThrowsExceptionWhenJsonIsInvalid()
	{
		Assert::throws(function() {
			$this->mapper->parseRequest('{"node: "invalid JSON"}');
		}, 'Drahak\Restful\Mapping\MappingException');
	}

}
\run(new JsonMapperTest());