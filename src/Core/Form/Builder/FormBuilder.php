<?php
namespace Core\Form\Builder;

use Core\Form\Element;
use Core\Form\Form;

class FormBuilder
{

	/**
	 * @var Element[]
	 */
	private $elements = [];

	/**
	 * @param string $action
	 * @param string $method
	 * @return FormBuilder
	 */
	public function openForm(string $action, string $method): self
	{
		$form = new Form($action, $method);
		$this->elements[] = $form;

		return $this;
	}

	/**
	 * @param Element $element
	 * @return FormBuilder
	 */
	public function add(Element $element): self
	{
		$this->elements[] = $element;

		return $this;
	}

	/**
	 * @return string
	 */
	public function build(): string
	{
		return join(' ', $this->elements) . ' ' . '</form>';
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->build();
	}

}
