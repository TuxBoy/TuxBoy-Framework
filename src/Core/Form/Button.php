<?php
namespace Core\Form;

class Button extends Element
{

	public function __construct(string $name)
	{
		parent::__construct('button', true);
		$this->setContent($name);
	}

}
