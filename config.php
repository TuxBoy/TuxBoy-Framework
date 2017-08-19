<?php

use function DI\env;
use Core\Priority;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

return [

	Priority::APP => [
        'db.name'   => env('DB_NAME'),
        'db.user'   => env('DB_USER', 'root'),
        'db.pass'   => env('DB_PASS', 'root'),
        'db.host'   => env('DB_HOST', 'localhost'),
        'db.driver' => env('DB_DRVER', 'pdo_mysql'),
        Connection::class => function (ContainerInterface $container) {
            return DriverManager::getConnection([
                'dbname'   => $container->get('db.name'),
                'user'     => $container->get('db.user'),
                'password' => $container->get('db.pass'),
                'host'     => $container->get('db.host'),
                'driver'   => $container->get('db.driver'),
            ]);
        },
	    Twig_Environment::class => function () {
            $loader = new Twig_Loader_Filesystem(__DIR__ . '/res/views');
            return new Twig_Environment($loader);
        }
    ],

	Priority::CORE => []

];
