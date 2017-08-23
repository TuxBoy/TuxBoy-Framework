<?php

use Core\Router\Router;
use TuxBoy\Application\Blog\Controller\BlogController;
use TuxBoy\Application\Home\Controller\HomeController;

$router = $app->container->get(Router::class);

$router->get('/', [HomeController::class, 'index'], 'root');
$router->get('/blog', [BlogController::class, 'index'], 'blog.index');
