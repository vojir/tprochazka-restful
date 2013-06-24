<?php
namespace Tests\Drahak\Restful\Tools\Documentation;

require_once __DIR__ . '/../../../../bootstrap.php';
require_once __DIR__ . '/ReflectionMethodMock.php';
require_once __DIR__ . '/ReflectionClassMock.php';
require_once __DIR__ . '/ClassFake.php';

use Drahak\Restful\Tools\Documentation\Generator;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Tools\Documentation\Generator.
 *
 * @testCase Tests\Drahak\Restful\Tools\Documentation\GeneratorTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Tools\Documentation
 */
class GeneratorTest extends TestCase
{

	/** @var MockInterface */
	private $loader;

	/** @var MockInterface */
	private $storage;

	/** @var MockInterface */
	private $resourceFactory;

	/** @var Generator */
	private $generator;
    
    protected function setUp()
    {
		parent::setUp();
		$this->loader = $this->mockista->create('Nette\Loaders\RobotLoader');
		$this->storage = $this->mockista->create('Nette\Caching\IStorage');
		$this->resourceFactory = $this->mockista->create('Drahak\Restful\Tools\Documentation\ResourceFactory');
		$this->generator = new Generator(__DIR__, $this->resourceFactory, $this->storage);
		$this->generator->setPresenterLoader($this->loader);
    }
    
    public function testGenerateDocumentationResource()
    {
		$class = 'Tests\Drahak\Restful\Tools\Documentation\ClassFake';
		$classes = array($class => 'stdClass.php');

		$this->loader->expects('tryLoad')
			->once()
			->with('Drahak\Restful\Application\IResourcePresenter');

		$this->loader->expects('getIndexedClasses')
			->once()
			->andReturn($classes);

		$this->resourceFactory->expects('createResourceDoc')
			->once();

		$this->generator->generate();
    }

}
\run(new GeneratorTest());