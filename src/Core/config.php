<?php

use Core\Database\Database;
use Core\Priority;
use Core\Tools\Whoops;
use Doctrine\DBAL\DriverManager;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;
use Psr\Container\ContainerInterface;
use function DI\env;
use function DI\string;
use function DI\object;
use function DI\get;

return [
    Priority::APP => [
	'dev'             => true,

	'basepath'        => dirname(dirname(__DIR__)),
	'aop.appDir'      => string('{basepath}/src/'),

	'db.name'         => env('DB_NAME'),
	'db.user'         => env('DB_USER', 'root'),
	'db.pass'         => env('DB_PASS', 'root'),
	'db.host'         => env('DB_HOST', 'localhost'),
	'db.driver'       => env('DB_DRVER', 'pdo_mysql'),
	Database::class   => function (ContainerInterface $container) {
		return DriverManager::getConnection([
			'dbname'       => $container->get('db.name'),
			'user'         => $container->get('db.user'),
			'password'     => $container->get('db.pass'),
			'host'         => $container->get('db.host'),
			'driver'       => $container->get('db.driver'),
			'wrapperClass' => Database::class
		]);
	},
	\Core\ApplicationApsect::class => function () {
		return \Core\ApplicationApsect::getInstance();
	},
	\Core\Aspect\MaintainerAspect::class => object()->constructor(get(Database::class)),
	\Core\App::class  => object(\Core\App::class),
	'twig.path'       => string('{basepath}/res/views'),
	'twig.extensions' => [
		object(\Core\Twig\RouterTwigExtension::class)
			->constructor(\DI\get(\Core\Router\Router::class))
	],
	\Core\Router\Router::class => object()->constructor(new Std(), new GroupCountBased()),
	Twig_Environment::class    => function (ContainerInterface $container) {
		$loader = new Twig_Loader_Filesystem($container->get('twig.path'));
		$twig = new Twig_Environment($loader);
		foreach ($container->get('twig.extensions') as $extension) {
			$twig->addExtension($extension);
		}

		return $twig;
	},
	\Core\Database\Maintainer::class =>
		object()->constructorParameter('database', get(Database::class)),
	\Core\Handler\HandlerInterface::class => object(Whoops::class),
    ],

	Priority::CORE => [],

	Priority::PLUGIN => [
		\Core\Aspect\MaintainerAspect::class
	]


];
