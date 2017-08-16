<?php
namespace Core\Concern;

/**
 * Trait Current
 * @package Core\Concern
 */
trait Current
{

	/**
	 * @var static
	 */
	private static $current;

    /**
     * @return static
     */
	public static function current()
	{
		if (is_null(static::$current)) {
			static::$current = new static();
		}
		return static::$current;
	}

}
