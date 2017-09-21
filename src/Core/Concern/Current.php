<?php

namespace TuxBoy\Concern;

/**
 * Trait Current.
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
        if (null === static::$current) {
            static::$current = new static();
        }

        return static::$current;
    }
}
