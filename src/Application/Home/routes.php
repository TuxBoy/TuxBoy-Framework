<?php

use Core\Router\Router;
use App\Blog\Controller\BlogController;
use App\Blog\Controller\CategoryController;
use App\Home\Controller\HomeController;

$router = $app->container->get(Router::class);

$router->get('/', [HomeController::class, 'index'], 'root');
