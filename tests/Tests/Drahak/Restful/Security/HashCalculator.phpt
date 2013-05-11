<?php
namespace Tests\Drahak\Restful\Security;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Security\HashCalculator;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Security\HashCalculator.
 *
 * @testCase Tests\Drahak\Restful\Security\HashCalculatorTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Security
 */
class HashCalculatorTest extends TestCase
{

	/** @var MockInterface */
	private $mapper;

	/** @var MockInterface */
	private $input;

	/** @var HashCalculator */
	private $calculator;

    protected function setUp()
    {
		parent::setUp();
		$this->mapper = $this->mockista->create('Drahak\Restful\Mapping\QueryMapper');
		$this->input = $this->mockista->create('Drahak\Restful\IInput');
		$this->calculator = new HashCalculator($this->mapper);
    }
    
    public function testCalculateHash()
    {
		$dataString = 'message=Testing+hash&sender=%40drahomir_hanak';
		$data = array('message' => 'Testing hash', 'sender' => '@drahomir_hanak');
		$this->mapper->expects('parseResponse')
			->once()
			->with($data)
			->andReturn($dataString);

		$this->input->expects('getData')
			->once()
			->andReturn($data);

		$this->calculator->setPrivateKey('topSecretKey');
		$hash = $this->calculator->calculate($this->input);

		Assert::equal(hash_hmac(HashCalculator::HASH, $dataString, 'topSecretKey'), $hash);
    }

}
\run(new HashCalculatorTest());