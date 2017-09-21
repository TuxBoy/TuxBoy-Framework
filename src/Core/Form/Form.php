<?php
namespace TuxBoy\Form;

class Form extends Element
{

	public function __construct(?string $action = null, ?string $method = null)
	{
		parent::__construct('form');
		if (!is_null($action)) $this->setAttribute('action', $action);
		if (!is_null($method)) $this->setAttribute('method', $method);
	}

}
