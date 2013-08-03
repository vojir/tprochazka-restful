<?php
namespace Tests\Drahak\Restful\Resource;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Resource\Link;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Resource\Link.
 *
 * @testCase Tests\Drahak\Restful\Resource\LinkTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Resource
 */
class LinkTest extends TestCase
{

	/** @var Link */
	private $link;

    public function setUp()
    {
		parent::setUp();
		$this->link = new Link('http://resource', Link::LAST);
    }
    
    public function testGetResourceData()
    {
		$data = $this->link->getData();
		Assert::equal($data['href'], 'http://resource');
		Assert::equal($data['rel'], Link::LAST);
    }

	public function testStringRepresentation()
	{
		$link = (string)$this->link;
		Assert::equal($link, '<http://resource>;rel="last"');
	}
    
}
\run(new LinkTest());
