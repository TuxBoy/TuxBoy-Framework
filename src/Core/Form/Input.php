<?php
namespace Core\Form;

class Input extends Element
{

	public function __construct(?string $name = null, ?string $value = null, ?string $id = null)
	{
		parent::__construct('input');
		if (!is_null($name))  $this->setAttribute('name', $name);
		if (!is_null($value)) $this->setAttribute('value', $value);
		if (!is_null($id))    $this->setAttribute('id', $id);
	}

}
