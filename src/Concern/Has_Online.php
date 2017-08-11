<?php
namespace App\Concern;

trait Has_Online
{

	/**
	 * @var boolean
	 */
	public $online;

	/**
	 * @return bool
	 */
	public function isOnline(): bool
	{
		return $this->online;
	}

	/**
	 * @param $online bool
	 */
	public function setOnline($online)
	{
		$this->online = $online;
	}

}
