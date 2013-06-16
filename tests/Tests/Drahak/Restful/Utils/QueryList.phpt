<?php
namespace Tests\Drahak\Restful\Utils;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Utils\QueryList;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Utils\QueryList.
 *
 * @testCase Tests\Drahak\Restful\Utils\QueryListTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Utils
 */
class QueryListTest extends TestCase
{

	/** @var QueryList */
	private $list;

    protected function setUp()
    {
		parent::setUp();
		$this->list = new QueryList(array('field', '-negate', 'another'));
    }
    
    public function testContainsNormalElement()
    {
		Assert::true($this->list->contains('field'));
    }

	public function testContainsInvertedElement()
	{
		Assert::true($this->list->contains('negate'));
	}

	public function testDoesNotContainElement()
	{
		Assert::false($this->list->contains('not_found'));
	}

	public function testIsElementInverted()
	{
		Assert::true($this->list->isInverted('negate'));
	}

	public function testElementIsNormal()
	{
		Assert::false($this->list->isInverted('field'));
	}

	public function testConvertQueryListToSimpleArray()
	{
		$array = $this->list->toArray();
		Assert::same($array, array('field', '-negate', 'another'));
	}

	public function testConvertToSortArray()
	{
		$array = $this->list->toSortArray();
		Assert::equal($array['field'], QueryList::ASC);
		Assert::equal($array['negate'], QueryList::DESC);
		Assert::equal($array['another'], QueryList::ASC);
	}

}
\run(new QueryListTest());