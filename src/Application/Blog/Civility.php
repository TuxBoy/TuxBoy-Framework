<?php

namespace TuxBoy\Application\Blog;

/**
 * Civility.
 */
trait Civility
{

	/**
	 * @length 60
	 * @var string
	 */
	public $code;

	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code;
	}

	/**
	 * @param $code string
	 */
	public function setCode($code)
	{
		$this->code = $code;
	}

}
