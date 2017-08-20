<?php

use function DI\env;
use Core\Priority;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;
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
        'app' => \DI\object(\Core\App::class),
        'twig.extensions' => [
            \DI\object(\Core\Twig\RouterTwigExtension::class)
                ->constructor(\DI\get(\Core\Router\Router::class))
        ],
        \Core\Router\Router::class => \DI\object()->constructor(new Std(), new GroupCountBased()),
	    Twig_Environment::class => function (ContainerInterface $container) {
            $loader = new Twig_Loader_Filesystem(__DIR__ . '/res/views');
            $twig   = new Twig_Environment($loader);
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
            return $twig;
        }
    ],

	Priority::CORE => [],

    Priority::PLUGIN => []

];
