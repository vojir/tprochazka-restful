<?php
namespace Tests\Drahak\Restful\Application;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Application\MethodAnnotation;
use Mockista\MockInterface;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Application\MethodAnnotation.
 *
 * @testCase Tests\Drahak\Restful\Application\MethodAnnotationTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Application
 */
class MethodAnnotationTest extends TestCase
{

	/** @var MockInterface */
	private $presenterReflection;

	/** @var MethodAnnotation */
	private $methodAnnotation;
    
    protected function setUp()
    {
		parent::setUp();
		$this->presenterReflection = $this->mockista->create('Nette\Reflection\ClassType');
		$this->methodAnnotation = new MethodAnnotation($this->presenterReflection, 'GET');
    }
    
    public function testCreateRoutesFromPresenterActionAnnotations()
    {
		$methodReflection = $this->mockista->create('Nette\Reflection\Method');
		$methodReflection->expects('hasAnnotation')
			->once()
			->with('GET')
			->andReturn(TRUE);
		$methodReflection->expects('getName')
			->atLeastOnce()
			->andReturn('actionTest');
		$methodReflection->expects('getAnnotation')
			->once()
			->with('GET')
			->andReturn('test/resource');

		$this->presenterReflection->expects('getMethods')
			->once()
			->andReturn(array($methodReflection));

		$this->presenterReflection->expects('getShortName')
			->once()
			->andReturn('TestPresenter');

		$routes = $this->methodAnnotation->getRoutes();
		Assert::true(isset($routes['Test:test']));
		Assert::equal($routes['Test:test'], 'test/resource');
    }
}
\run(new MethodAnnotationTest());