<?php

use Core\Database\Database;
use Core\Handler\HandlerInterface;
use Core\Priority;
use Core\Router\Router;
use Core\Session\PHPSession;
use Core\Session\SessionInterface;
use Core\Tools\Whoops;
use Core\Twig\FlashExtension;
use Core\Twig\RouterTwigExtension;
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
        'aspect.appDir'   => string('{basepath}/src/'),
        'aspect.cacheDir' => false,

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
        \Go\Core\AspectKernel::class => function (ContainerInterface $container) {
            $applicationKernel = \Core\ApplicationApsect::getInstance();
            $applicationKernel->init([
                'debug'        => $container->get('dev'),
                'appDir'       => $container->get('aspect.appDir'),
                'cacheDir'     => $container->get('aspect.cacheDir'),
                'includePaths' => []
            ]);
            return $applicationKernel;
        },
        \Go\Core\AspectContainer::class => function (ContainerInterface $container) {
            $kernel = $container->get(\Go\Core\AspectKernel::class);
            return $kernel->getContainer();
        } ,
        'twig.path'       => string('{basepath}/res/views'),
        'twig.extensions' => [
            object(RouterTwigExtension::class)->constructor(get(Router::class)),
            get(FlashExtension::class)

        ],
        Router::class => object()->constructor(new Std(), new GroupCountBased()),
        Twig_Environment::class    => function (ContainerInterface $container) {
            $loader = new Twig_Loader_Filesystem($container->get('twig.path'));
            $twig = new Twig_Environment($loader);
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }

            return $twig;
        },
        \Go\Core\AspectContainer::class => object(\Go\Core\GoAspectContainer::class),
        'goaop.aspect' => [
            object(\Core\Aspect\MaintainerAspect::class)->constructor(get(Database::class), get('dev'))
        ],
        \Core\Database\Maintainer::class =>
            object()->constructorParameter('database', get(Database::class)),
        HandlerInterface::class => object(Whoops::class),
        SessionInterface::class => object(PHPSession::class)
    ],

	Priority::CORE => [],

	Priority::PLUGIN => []

];
