<?php
namespace Tests\Drahak\Restful;

require_once __DIR__ . '/../../bootstrap.php';

use Drahak\Restful\IResource;
use Drahak\Restful\Resource\ResourceConverter;
use Drahak\Restful\ResourceFactory;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\ResourceFactory.
 *
 * @testCase Tests\Drahak\Restful\ResourceFactoryTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful
 */
class ResourceFactoryTest extends TestCase
{

    /** @var MockInterface */
    private $request;

	/** @var ResourceConverter */
	private $resourceConverter;

    /** @var ResourceFactory */
    private $factory;

    public function setUp()
    {
		parent::setUp();
        $this->request = $this->mockista->create('Drahak\Restful\Http\IRequest');
        $this->resourceConverter = $this->mockista->create('Drahak\Restful\Resource\ResourceConverter');
        $this->factory = new ResourceFactory($this->request, $this->resourceConverter);
    }
    
    public function testCreateResource()
    {
        $this->request->expects('getPreferredContentType')
            ->once()
            ->andReturn('application/json');

        $resource = $this->factory->create();
        Assert::true($resource instanceof IResource);
        Assert::equal($resource->getContentType(), 'application/json');
    }
    
}
\run(new ResourceFactoryTest());
