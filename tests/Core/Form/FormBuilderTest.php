<?php
namespace Test\TuxBoy\Form;

use TuxBoy\Form\Builder\FormBuilder;
use TuxBoy\Form\Input;
use PHPUnit\Framework\TestCase;

class FormBuilderTest extends TestCase
{

	public function testBuildSimpleForm()
	{

		$formBuilder = new FormBuilder('/contact', 'POST');
		$email = new Input('email');
		$email->setAttribute('type', 'email');
		$formBuilder
			->openForm('/contact', 'POST')
			->add((new Input('username'))->setAttribute('placeholder', 'Username'))
			->add($email)
			->build();
		$this->assertEquals(
			'<form action="/contact" method="POST"> <input name="username" placeholder="Username"> <input name="email" type="email"> </form>',
			(string) $formBuilder
		);
	}

}
