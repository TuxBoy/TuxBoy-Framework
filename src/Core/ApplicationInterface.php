<?php

namespace TuxBoy;

use TuxBoy\Router\Router;

interface ApplicationInterface
{
    /**
     * Définie les routes de l'application.
     *
     * @param Router $router
     */
    public function getRoutes(Router $router): void;

    /**
     * Pour ajouter la configuration au container de son application.
     *
     * @return array
     */
    public function addConfig(): array;

    /**
     * @return string
     */
    public function getName(): string;
}
