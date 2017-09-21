<?php

namespace TuxBoy\Plugin;

/**
 * Registrable.
 */
interface Registrable extends PluginInterface
{
    /**
     * Registrable constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration = []);
}
