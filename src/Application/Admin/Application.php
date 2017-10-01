<?php
namespace App\Admin;

use App\Admin\Controller\AdminController;
use TuxBoy\ApplicationInterface;
use TuxBoy\Router\Router;
use function DI\add;

/**
 * The Admin application
 */
class Application implements ApplicationInterface
{
    /**
     * Définie les routes de l'application.
     *
     * @param Router $router
     */
    public function getRoutes(Router $router): void
    {
        $router->get('/admin/dashboard', [AdminController::class, 'index'], 'admin.index');
    }

    /**
     * Pour ajouter la configuration au container de son application.
     *
     * @return array
     */
    public function addConfig(): array
    {
        return [
            'admin.prefix' => 'admin',
            'twig.path' => add([
               'admin'  => __DIR__ . '/views/'
            ]),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Admin';
    }
}
