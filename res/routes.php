<?php

use Core\Router\Router;
use TuxBoy\Application\Blog\Controller\BlogController;
use TuxBoy\Application\Blog\Controller\CategoryController;
use TuxBoy\Application\Home\Controller\HomeController;

$router = $app->container->get(Router::class);

$router->get('/', [HomeController::class, 'index'], 'root');
$router->get('/blog', [BlogController::class, 'index'], 'blog.index');
$router->get('/blog/new', [BlogController::class, 'create'], 'blog.new');
$router->get('/blog/list', [BlogController::class, 'listToArticles'], 'blog.list.articles');
$router->get('/blog/{slug}', [BlogController::class, 'show'], 'blog.show');
$router->post('/blog/new', [BlogController::class, 'create']);
$router->get('/blog/editer/{id}', [BlogController::class, 'udpate'], 'blog.update');
$router->get('/blog/categorie/new', [CategoryController::class, 'create'], 'blog.category.new');
$router->post('/blog/categorie/new', [CategoryController::class, 'create']);
