<?php
namespace Tests\Drahak\Restful;

require_once __DIR__ . '/../../bootstrap.php';

use Drahak\Restful\IResource;
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

	/** @var MockInterface */
	private $resourceConverter;

    /** @var ResourceFactory */
    private $factory;

    public function setUp()
    {
		parent::setUp();
        $this->request = $this->mockista->create('Nette\Http\IRequest');
        $this->resourceConverter = $this->mockista->create('Drahak\Restful\Converters\ResourceConverter');
        $this->factory = new ResourceFactory($this->request, $this->resourceConverter);
    }
    
    public function testCreateResource()
    {
        $this->request->expects('getHeader')
            ->once()
            ->with('Accept')
            ->andReturn('application/json');

        $resource = $this->factory->create();
        Assert::true($resource instanceof IResource);
        Assert::equal($resource->getContentType(), 'application/json');
    }

	public function testCreateResourceWithDefaultData()
	{
		$data = array('test' => 'factory');

        $this->request->expects('getHeader')
            ->once()
            ->with('Accept')
            ->andReturn('application/json');
		$this->resourceConverter->expects('convert')
			->once()
			->with($data)
			->andReturn($data);

		$resource = $this->factory->create($data);
		Assert::true($resource instanceof IResource);
		Assert::equal($resource->getContentType(), 'application/json');
		Assert::same($resource->getData(), $data);
	}

	public function testUseFirstAcceptableContentTypeFromAcceptHeader()
	{
        $this->request->expects('getHeader')
            ->once()
            ->with('Accept')
            ->andReturn('text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8');
		$this->resourceConverter->expects('convert')->once();

		$resource = $this->factory->create();
		Assert::equal($resource->getContentType(), 'application/xml');
	}

	public function testThrowsExceptionIfAcceptHeaderIsUnknown()
	{
        $this->request->expects('getHeader')
            ->once()
            ->with('Accept')
            ->andReturn('text/html,text/plain,text/*');
		$this->resourceConverter->expects('convert')->once();

		Assert::exception(function() {
			$this->factory->create();
		}, 'Drahak\Restful\InvalidStateException');	
	}

	public function testUseDefaultContentTypeWhenAvailable()
	{
        $this->request->expects('getHeader')
            ->once()
            ->with('Accept')
            ->andReturn('text/html,text/plain,text/*');
		$this->resourceConverter->expects('convert')->once();		

		$resource = $this->factory->create(array(), IResource::XML);
		Assert::equal($resource->getContentType(), 'application/xml');
	}

}
\run(new ResourceFactoryTest());
