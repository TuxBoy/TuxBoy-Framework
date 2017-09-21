<?php
namespace Test\TuxBoy;

use TuxBoy\ReflectionAnnotation;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class FakePost
 *
 * @set posts
 */
class FakePost {

	/**
	 * @length 20
	 * @var string
	 */
	public $name;

	/**
	 * @longtext
	 */
	public $content;
}

class ReflectionAnnotationTest extends TestCase
{

	/**
	 * @param null|string $property_name
	 * @return ReflectionAnnotation
	 */
	private function makeReflectionAnnotation(?string $property_name = null): ReflectionAnnotation
	{
		return new ReflectionAnnotation(FakePost::class, $property_name);
	}

	public function testGetAnnotationProperty()
	{
		$reflection_annotation = $this->makeReflectionAnnotation('name')->getAnnotation('var');
		$this->assertEquals('var', $reflection_annotation->getName());
		$this->assertEquals('string', $reflection_annotation->getValue());
	}

	public function testGetAnnotationWithoutValue()
	{
		$reflection_annotation = $this->makeReflectionAnnotation('content')->getAnnotation('longtext');
		$this->assertEquals('longtext', $reflection_annotation->getName());
		$this->assertNull($reflection_annotation->getValue());
	}

	public function testGetAnnotationClass()
	{
		$reflection_annotation = $this->makeReflectionAnnotation()->getAnnotation('set');
		$this->assertEquals('set', $reflection_annotation->getName());
	}

	public function testHasAnnotation()
	{
		$reflection_annotation = $this->makeReflectionAnnotation('name');
		$this->assertTrue($reflection_annotation->hasAnnotation('var'));
	}

	public function testHasNotAnnotation()
	{
		$reflection_annotation = $this->makeReflectionAnnotation('content');
		$this->assertFalse($reflection_annotation->hasAnnotation('var'));
	}
}
