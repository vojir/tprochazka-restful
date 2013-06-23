<?php
namespace Tests\Drahak\Restful\Utils;

require_once __DIR__ . '/../../../bootstrap.php';

use Drahak\Restful\Utils\Strings;
use Nette;
use Tester;
use Tester\Assert;
use Tests\TestCase;

/**
 * Test: Tests\Drahak\Restful\Utils\Strings.
 *
 * @testCase Tests\Drahak\Restful\Utils\StringsTest
 * @author Drahomír Hanák
 * @package Tests\Drahak\Restful\Utils
 */
class StringsTest extends TestCase
{

	public function testConvertsFirstLetterToLowerCase()
	{
		$string = Strings::firstLower('LOWER');
		Assert::equal($string, 'lOWER');
	}

    public function testConvertsStringToCamelCase()
    {
		$camel = Strings::toCamelCase('Just a normal sentence');
		Assert::equal($camel, 'justANormalSentence');
    }

	public function testConvertsStringToSnakeCase()
	{
		$snake = Strings::toSnakeCase('someCamel or_any-other case');
		Assert::equal($snake, 'some_camel_or_any_other_case');
	}

	public function testConvertsStringToPascalCase()
	{
		$pascal = Strings::toPascalCase('just a normal sentence');
		Assert::equal($pascal, 'JustANormalSentence');
	}

}
\run(new StringsTest());