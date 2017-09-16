<?php
namespace Core;

use Core\Router\Router;

abstract class Application
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
    abstract public function getRoutes(Router $router): void;

    /**
     * Pour ajouter la configuration au container de son application
     *
     * @return array
     */
    abstract public function addConfig(): array;

}