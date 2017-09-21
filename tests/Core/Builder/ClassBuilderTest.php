<?php
namespace Test\TuxBoy\Builder;

use TuxBoy\Builder\ClassBuilder;
use TuxBoy\Builder\Namespaces;
use PHPUnit\Framework\TestCase;
use Test\TuxBoy\Entity\FakeEntity;

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
		$fake_namespace = '\\App\\Model\\Post';
		$this->assertEquals('\\App\\Model', Namespaces::getNamespace($fake_namespace));
		$class_without_namespace = 'Post';
		$this->assertEmpty(Namespaces::getNamespace($class_without_namespace));
	}

	public function testShortClassName()
	{
		$fake_namespace = '\\App\\Model\\Post';
		$this->assertEquals('Post', Namespaces::shortClassName($fake_namespace));
		$class_without_namespace = 'Post';
		$this->assertEquals('Post', Namespaces::shortClassName($class_without_namespace));
	}

}
