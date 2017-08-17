<?php
namespace Test\Core\Builder;

use Core\Builder\Class_Builder;
use PHPUnit\Framework\TestCase;

class ClassBuilderTest extends TestCase
{

	public function testGetNameSpace()
	{
		$fake_namespace = '\\App\\Model\\Article';
		$this->assertEquals('\\App\\Model', Class_Builder::getNamespace($fake_namespace));
		$class_without_namespace = 'Article';
		$this->assertEmpty(Class_Builder::getNamespace($class_without_namespace));
	}

	public function testShortClassName()
	{
		$fake_namespace = '\\App\\Model\\Article';
		$this->assertEquals('Article', Class_Builder::shortClassName($fake_namespace));
		$class_without_namespace = 'Article';
		$this->assertEquals('Article', Class_Builder::shortClassName($class_without_namespace));
	}

}
