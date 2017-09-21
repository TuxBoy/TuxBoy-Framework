<?php
namespace Core\Form;

class Attribute
{

	/**
	 * @var null|string
	 */
	private $name;

	/**
	 * @var null
	 */
	private $value;

	public function __construct(?string $name = null, ?string $value = null)
	{
		$this->name = $name;
		$this->value = $value;
	}

	public function __toString()
	{
		return $this->name . (!is_null($this->value) ? '="' . $this->value . '"' : '');
	}

	/**
	 * @return null|string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return null
	 */
	public function getValue()
	{
		return $this->value;
	}

}
