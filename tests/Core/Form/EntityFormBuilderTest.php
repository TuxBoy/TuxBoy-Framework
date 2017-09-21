<?php
namespace Test\TuxBoy\Form;


use PHPUnit\Framework\TestCase;
use TuxBoy\Entity;
use TuxBoy\Form\Builder\EntityFormBuilder;
use TuxBoy\Form\Builder\FormBuilder;

class Fake extends Entity
{
	/**
	 * @var string
	 */
	public $name;
}

class Demo extends Entity
{

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $slug;

	/**
	 * @var text
	 */
	public $content;

}

class EntityFormBuilderTest extends TestCase
{

	/**
	 * @var EntityFormBuilder
	 */
	private $builder;

	/**
	 * @var FormBuilder
	 */
	private $formBuilder;

	public function setUp()
	{
		$this->formBuilder = new FormBuilder();
		$this->builder = new EntityFormBuilder($this->formBuilder);
	}

	public function testGenerateSimpleForm()
	{
		$entity = new Fake;
		$this->assertEquals(
			'<form action="/demo" method="POST"> <input name="name" type="text"> <button type="submit">Envoyer</button> </form>',
			$this->builder->generateForm($entity, '/demo')
		);
	}

	public function testGenerateFormWithParameters()
	{
		$entity = new Demo();
		$this->assertEquals(
			'<form action="/demo" method="POST"> <input name="name" type="text"> <input name="slug" type="text"> <textarea name="content"></textarea> <button type="submit">Envoyer</button> </form>',
			$this->builder->generateForm($entity, '/demo')
		);
	}

	public function testGenerateFormWithData()
	{
		$entity = new Demo();
		$entity->name = 'joe';
		$entity->slug = 'un-test';
		$entity->content = 'Du contenu';
		$this->assertEquals(
			'<form action="/demo" method="POST"> <input name="name" value="joe" type="text"> <input name="slug" value="un-test" type="text"> <textarea name="content">Du contenu</textarea> <button type="submit">Envoyer</button> </form>',
			$this->builder->generateForm($entity, '/demo')
		);
	}

}
