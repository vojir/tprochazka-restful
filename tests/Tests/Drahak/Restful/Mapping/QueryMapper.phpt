<?php
namespace Tests\Drahak\Restful\Mapping;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Mapping\QueryMapper;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Mapping\QueryMapper.
 *
 * @testCase Tests\Drahak\Restful\Mapping\QueryMapperTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Mapping
 */
class QueryMapperTest extends TestCase
{

	/** @var QueryMapper */
	private $mapper;

    protected function setUp()
    {
		parent::setUp();
		$this->mapper = new QueryMapper;
    }
    
    public function testParseRequest()
    {
		$query = 'message=Follow+me+on+Twitter&sender=%40drahomir_hanak';
		$data = $this->mapper->parseRequest($query);
		Assert::equal($data['message'], 'Follow me on Twitter');
		Assert::equal($data['sender'], '@drahomir_hanak');
    }

	public function testParseResponse()
	{
		$data['message'] = 'Follow me on Twitter';
		$data['sender'] = '@drahomir_hanak';
		$query = $this->mapper->parseResponse($data);
		Assert::equal($query, 'message=Follow+me+on+Twitter&sender=%40drahomir_hanak');
	}

}
\run(new QueryMapperTest());