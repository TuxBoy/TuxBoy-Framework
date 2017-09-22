<?php
namespace App\User;

use Core\ApplicationInterface;
use Core\Router\Router;

class Application implements ApplicationInterface
{

    /**
     * Définie les routes de l'application.
     *
     * @param Router $router
     */
    public function getRoutes(Router $router): void
    {
        $router->get('/user', function () { return 'Coucou'; });
    }

    /**
     * Pour ajouter la configuration au container de son application
     *
     * @return array
     */
    public function addConfig(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'User';
    }
}