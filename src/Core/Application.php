<?php

namespace TuxBoy;

use TuxBoy\Router\Router;

class Application implements ApplicationInterface
{
    public function getName(): string
    {
        return str_replace('\\Application', '', get_class($this));
    }

    /**
     * Définie les routes de l'application.
     *
     * @param Router $router
     */
    public function getRoutes(Router $router): void
    {
        // TODO: Implement getRoutes() method.
    }

    /**
     * Pour ajouter la configuration au container de son application.
     *
     * @return array
     */
    public function addConfig(): array
    {
        // TODO: Implement addConfig() method.
    }
}
