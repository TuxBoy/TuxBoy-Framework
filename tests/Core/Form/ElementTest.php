<?php
namespace Test\TuxBoy\Form;

use TuxBoy\Form\Checkbox;
use TuxBoy\Form\Input;
use TuxBoy\Form\Select;
use TuxBoy\Form\Textarea;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{

	public function testInputField()
	{
		$input = new Input('username');
		$this->assertEquals('<input name="username">', (string) $input);
		$input = new Input('username', 'doe');
		$this->assertEquals('<input name="username" value="doe">', (string) $input);
	}

	public function testTextareaField()
	{
		$textarea = new Textarea('content');
		$this->assertEquals('<textarea name="content"></textarea>', (string) $textarea);
	}

	public function testTextareaFieldWithContent()
	{
		$textarea = new Textarea('content', 'Du contenu ici');
		$this->assertEquals('<textarea name="content">Du contenu ici</textarea>', (string) $textarea);
	}

	public function testSelectElement()
	{
		$values = [1 => 'Films', 2 => 'test'];
		$select = new Select('categories');
		$this->assertEquals('<select name="categories"></select>', (string) $select);
		$output = '<select name="categories"><option value="1">Films</option><option value="2">test</option></select>';
		$select = new Select('categories', $values);
		$this->assertEquals($output, (string) $select);
	}

	public function testSelectWithSelected()
	{
		$values = [1 => 'Films', 2 => 'test'];
		$select = new Select('categories', $values, 'test');
		$output = '<select name="categories"><option value="1">Films</option><option value="2" selected>test</option></select>';
		$this->assertEquals($output, (string) $select);
	}

}
