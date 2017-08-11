<?php
namespace Core\Concern;

/**
 * Trait Current
 * @package Core\Concern
 */
trait Current
{

	/**
	 * @var self
	 */
	private static $current;

	public static function current() : self
	{
		if (is_null(self::$current)) {
			self::$current = new self();
		}
		return self::$current;
	}

}
