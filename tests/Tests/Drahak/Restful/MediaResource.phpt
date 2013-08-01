<?php
namespace Tests\Drahak\Restful;

require_once __DIR__ . '/../../bootstrap.php';

use Drahak\Restful\MediaResource;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\MediaResource.
 *
 * @testCase Tests\Drahak\Restful\MediaResourceTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful
 */
class MediaResourceTest extends TestCase
{

	/** @var MockInterface */
	private $media;

	/** @var MediaResource */
	private $resource;

    public function setUp()
    {
		parent::setUp();
		$this->media = $this->mockista->create('Drahak\Restful\Media');
		$this->resource = new MediaResource($this->media);
    }
    
    public function testGetMediaObjectAsResourceData()
    {
		$data = $this->resource->getData();
		Assert::same($data, $this->media);
    }
    
}
\run(new MediaResourceTest());
