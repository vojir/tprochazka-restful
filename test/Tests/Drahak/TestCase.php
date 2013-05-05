<?php
namespace Tests\Drahak;

use Mockista\Registry;
use Tester;

/**
 * TestCase
 * @package Tests\Drahak
 * @author Drahomír Hanák
 */
class TestCase extends Tester\TestCase
{

    /** @var Registry */
    protected $mockista;

    protected function setUp()
    {
        $this->mockista = new Registry;
    }

    protected function tearDown()
    {
        $this->mockista->assertExpectations();
    }

}