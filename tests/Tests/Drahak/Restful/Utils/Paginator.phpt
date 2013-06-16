<?php
namespace Tests\Drahak\Restful\Utils;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Utils\Paginator;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Utils\Paginator.
 *
 * @testCase Tests\Drahak\Restful\Utils\PaginatorTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Utils
 */
class PaginatorTest extends TestCase
{

	/** @var Paginator */
	private $paginator;

    protected function setUp()
    {
		parent::setUp();
		$this->paginator = new Paginator;
		$this->paginator->setItemCount(100);
		$this->paginator->setItemsPerPage(10);
		$this->paginator->setPage(9);
		$this->paginator->setUrl('http://localhost/api/v1/');
    }
    
    public function testGetNextPageUrl()
    {
		$nextPageUrl = (string)$this->paginator->getNextPageUrl();
		Assert::equal($nextPageUrl, 'http://localhost/api/v1/?offset=90&limit=10');
    }

	public function testGetLastPageUrl()
	{
		$this->paginator->setPage(2);

		$lastPageUrl = (string)$this->paginator->getLastPageUrl();
		Assert::equal($lastPageUrl, 'http://localhost/api/v1/?offset=90&limit=10');
	}

}
\run(new PaginatorTest());