<?php
namespace App\Home;

use App\Home\Controller\HomeController;
use Core;
use Core\Router\Router;

class Application extends Core\Application
{
    /**
     * @param Router $router
     */
    public function getRoutes(Router $router): void
    {
        $router->get('/', [HomeController::class, 'index'], 'root');
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
}