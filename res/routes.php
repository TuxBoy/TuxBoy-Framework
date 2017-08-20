<?php

use Core\Router\Router;
use TuxBoy\Application\Controller\HomeController;
use TuxBoy\Application\Controller\WorkController;

$router = $app->container->get(Router::class);

$router->get('/', [HomeController::class, 'index'], 'root');
$router->get('/portfolio', [WorkController::class, 'index'], 'work.index');