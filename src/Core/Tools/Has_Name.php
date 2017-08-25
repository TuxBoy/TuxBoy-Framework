<?php
namespace Core\Tools;

trait Has_Name
{

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param $name string
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
}
