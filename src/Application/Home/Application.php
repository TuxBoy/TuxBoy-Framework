<?php

namespace App\Home;

use App\Home\Controller\HomeController;
use TuxBoy\ApplicationInterface;
use TuxBoy\Router\Router;
use function DI\add;

class Application implements ApplicationInterface
{
    /**
     * @param Router $router
     */
    public function getRoutes(Router $router): void
    {
        $router->get('/', [HomeController::class, 'index'], 'root');
    }

    /**
     * Pour ajouter la configuration au container de son application.
     *
     * @return array
     */
    public function addConfig(): array
    {
        return [
            'twig.path' => add([
                'home' => __DIR__ . '/views/'
            ]),
        ];
    }

    public function getName(): string
    {
        return str_replace('\\Application', '', get_class($this));
    }
}
