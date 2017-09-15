<?php

use Core\Aspect\MaintainerAspect;
use Core\Database\Database;
use Core\Database\Maintainer;
use Core\Handler\HandlerInterface;
use Core\Priority;
use Core\Router\Router;
use Core\Session\PHPSession;
use Core\Session\SessionInterface;
use Core\Tools\Whoops;
use Core\Twig\FlashExtension;
use Core\Twig\RouterTwigExtension;
use Core\Twig\TwigFactory;
use Doctrine\DBAL\DriverManager;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;
use Go\Core\AspectContainer;
use Go\Core\AspectKernel;
use Go\Core\GoAspectContainer;
use Psr\Container\ContainerInterface;
use function DI\env;
use function DI\factory;
use function DI\get;
use function DI\object;
use function DI\string;

return [
    Priority::APP => [
            'dev'             => true,

    'migration.auto'          => true,
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
            AspectKernel::class => function (ContainerInterface $container) {
                $applicationKernel = \Core\ApplicationApsect::getInstance();
                $applicationKernel->init([
                            'debug'        => $container->get('dev'),
                            'appDir'       => $container->get('aspect.appDir'),
                            'cacheDir'     => $container->get('aspect.cacheDir'),
                            'includePaths' => []
                    ]);

                return $applicationKernel;
            },
            AspectContainer::class => function (ContainerInterface $container) {
                $kernel = $container->get(AspectKernel::class);

                return $kernel->getContainer();
            },
            'twig.path'       => string('{basepath}/res/views'),
            'twig.extensions' => [
                    get(RouterTwigExtension::class),
                    get(FlashExtension::class)
            ],
            Std::class => object(),
            GroupCountBased::class => object(),
            \FastRoute\RouteCollector::class => object()->constructor(get(Std::class), get(GroupCountBased::class)),
            Router::class           => object()->constructor(get(\FastRoute\RouteCollector::class)),
            Twig_Environment::class => factory(TwigFactory::class),
            AspectContainer::class  => object(GoAspectContainer::class),
            'goaop.aspect'          => [
                    object(MaintainerAspect::class)
            ->constructor(get(Maintainer::class), get('dev'), get('migration.auto'))
            ],
            Maintainer::class       => object()->constructorParameter('database', get(Database::class)),
            HandlerInterface::class => object(Whoops::class),
            SessionInterface::class => object(PHPSession::class)
    ],

    Priority::CORE => [],

    Priority::PLUGIN => []
];
