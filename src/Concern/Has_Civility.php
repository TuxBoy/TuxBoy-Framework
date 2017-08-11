<?php

namespace App\Concern;

/**
 * Has_Civility.
 */
trait Has_Civility
{

	/**
	 * @var string
	 */
	private $civility;

	/**
	 * @return string
	 */
	public function getCivility(): string
	{
		return $this->civility;
	}

	/**
	 * @param $civility string
	 */
	public function setCivility($civility)
	{
		$this->civility = $civility;
	}

}
