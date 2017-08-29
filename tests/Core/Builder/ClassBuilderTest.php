<?php
namespace Test\Core\Builder;

use Core\Builder\ClassBuilder;
use Core\Builder\Namespaces;
use PHPUnit\Framework\TestCase;
use Test\Core\Entity\FakeEntity;

trait FakeOnline
{

	/**
	 * @var bool
	 */
	public $online = false;
}

class ClassBuilderTest extends TestCase
{

	public function testGetNameSpace()
	{
		$fake_namespace = '\\App\\Model\\Article';
		$this->assertEquals('\\App\\Model', Namespaces::getNamespace($fake_namespace));
		$class_without_namespace = 'Article';
		$this->assertEmpty(Namespaces::getNamespace($class_without_namespace));
	}

	public function testShortClassName()
	{
		$fake_namespace = '\\App\\Model\\Article';
		$this->assertEquals('Article', Namespaces::shortClassName($fake_namespace));
		$class_without_namespace = 'Article';
		$this->assertEquals('Article', Namespaces::shortClassName($class_without_namespace));
	}

}
