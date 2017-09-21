<?php
namespace TuxBoy\Form;

class Textarea extends Element
{

	public function __construct(?string $name = null, ?string $value = null, ?string $id = null)
	{
		parent::__construct('textarea', true);
		if (!is_null($name)) $this->setAttribute('name', $name);
		if (!is_null($value)) $this->setContent($value);
	}

}
