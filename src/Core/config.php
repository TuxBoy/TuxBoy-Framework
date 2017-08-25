<?php

use Core\Database\Database;
use Core\Priority;
use Core\Tools\Whoops;
use Doctrine\DBAL\DriverManager;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;
use Psr\Container\ContainerInterface;
use function DI\env;

return [
    Priority::APP => [
	'dev'       => true,

	'basepath'        => dirname(dirname(__DIR__)),
	'db.name'         => env('DB_NAME'),
	'db.user'         => env('DB_USER', 'root'),
	'db.pass'         => env('DB_PASS', 'root'),
	'db.host'         => env('DB_HOST', 'localhost'),
	'db.driver'       => env('DB_DRVER', 'pdo_mysql'),
	Database::class => function (ContainerInterface $container) {
		return DriverManager::getConnection([
			'dbname'       => $container->get('db.name'),
			'user'         => $container->get('db.user'),
			'password'     => $container->get('db.pass'),
			'host'         => $container->get('db.host'),
			'driver'       => $container->get('db.driver'),
			'wrapperClass' => Database::class
		]);
	},
	'app'             => \DI\object(\Core\App::class),
	'twig.path'       => \DI\string('{basepath}/res/views'),
	'twig.extensions' => [
		\DI\object(\Core\Twig\RouterTwigExtension::class)
			->constructor(\DI\get(\Core\Router\Router::class))
	],
	\Core\Router\Router::class => \DI\object()->constructor(new Std(), new GroupCountBased()),
	Twig_Environment::class    => function (ContainerInterface $container) {
		$loader = new Twig_Loader_Filesystem($container->get('twig.path'));
		$twig = new Twig_Environment($loader);
		foreach ($container->get('twig.extensions') as $extension) {
			$twig->addExtension($extension);
		}

		return $twig;
	},
	\Core\Database\Maintainer::class =>
		\DI\object()->constructorParameter('database', \DI\get(Database::class)),
	\Core\Handler\HandlerInterface::class => \DI\object(Whoops::class),
    ],

	Priority::CORE => [],

	Priority::PLUGIN => [
		\Core\Aspect\MaintainerAspect::class
	]


];
