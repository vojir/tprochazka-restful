<?php
namespace Tests\Drahak\Restful\Mapping;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Mapping\DataUrlMapper;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Mapping\DataUrlMapper.
 *
 * @testCase Tests\Drahak\Restful\Mapping\DataUrlMapperTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Mapping
 */
class DataUrlMapperTest extends TestCase
{

	/** @var DataUrlMapper */
	private $mapper;

    protected function setUp()
    {
		parent::setUp();
		$this->mapper = new DataUrlMapper;
    }
    
    public function testEncodeContentToBase64WithMimeTypeFromArray()
    {
		$encoded = $this->mapper->stringify(array('src' => 'Hello world', 'type' => 'text/plain'));
		Assert::equal($encoded, 'data:text/plain;base64,SGVsbG8gd29ybGQ=');
    }

	public function testDecodeBase64DataToArray()
	{
		$data = array('src' => 'Hello world', 'type' => 'text/plain');
		$result = $this->mapper->parse('data:text/plain;base64,SGVsbG8gd29ybGQ=');
		Assert::equal($result, $data);
	}

}
\run(new DataUrlMapperTest());