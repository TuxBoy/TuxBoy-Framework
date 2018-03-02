<?php
namespace App\Link;

use App\Link\Controller\LinkController;
use App\Link\Entity\Link;
use TuxBoy\ApplicationInterface;
use TuxBoy\Router\Router;

class Application implements ApplicationInterface
{

    /**
     * Définie les routes de l'application.
     *
     * @param Router $router
     */
    public function getRoutes(Router $router): void
    {
        $router->get('/links', [LinkController::class, 'index'], 'link.index');
        $router->get('/link/add', [LinkController::class, 'create'], 'link.add');
        $router->post('/link/add', [LinkController::class, 'create']);
    }

    /**
     * Pour ajouter la configuration au container de son application.
     *
     * @return array
     */
    public function addConfig(): array
    {
        return require __DIR__ . '/config.php';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Link';
    }
}
