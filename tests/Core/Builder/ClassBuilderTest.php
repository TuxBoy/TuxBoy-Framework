<?php
namespace Test\Core\Builder;

use Core\Builder\ClassBuilder;
use PHPUnit\Framework\TestCase;

class ClassBuilderTest extends TestCase
{

	public function testGetNameSpace()
	{
		$fake_namespace = '\\App\\Model\\Article';
		$this->assertEquals('\\App\\Model', ClassBuilder::getNamespace($fake_namespace));
		$class_without_namespace = 'Article';
		$this->assertEmpty(ClassBuilder::getNamespace($class_without_namespace));
	}

	public function testShortClassName()
	{
		$fake_namespace = '\\App\\Model\\Article';
		$this->assertEquals('Article', ClassBuilder::shortClassName($fake_namespace));
		$class_without_namespace = 'Article';
		$this->assertEquals('Article', ClassBuilder::shortClassName($class_without_namespace));
	}

}
