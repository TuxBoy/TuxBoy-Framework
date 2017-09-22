<?php

use Doctrine\DBAL\DriverManager;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;
use Go\Core\AspectContainer;
use Go\Core\AspectKernel;
use Go\Core\GoAspectContainer;
use Psr\Container\ContainerInterface;
use TuxBoy\Annotation\Length;
use TuxBoy\Annotation\Set;
use TuxBoy\Annotation\Option;
use TuxBoy\Aspect\MaintainerAspect;
use TuxBoy\Database\Database;
use TuxBoy\Database\Maintainer;
use TuxBoy\Handler\HandlerInterface;
use TuxBoy\Priority;
use TuxBoy\Router\Router;
use TuxBoy\Session\PHPSession;
use TuxBoy\Session\SessionInterface;
use TuxBoy\Tools\Whoops;
use TuxBoy\Twig\FlashExtension;
use TuxBoy\Twig\FormExtension;
use TuxBoy\Twig\RouterTwigExtension;
use TuxBoy\Twig\TwigFactory;
use function DI\add;
use function DI\env;
use function DI\factory;
use function DI\get;
use function DI\object;
use function DI\string;

return [
    Priority::APP => [
        'dev'             => true,

        'migration.auto'  => true,
        'basepath'        => dirname(dirname(__DIR__)),
        'aspect.appDir'   => string('{basepath}/src/'),
        'aspect.cacheDir' => false,

        'db.name'                          => env('DB_NAME'),
        'db.user'                          => env('DB_USER', 'root'),
        'db.pass'                          => env('DB_PASS', 'root'),
        'db.host'                          => env('DB_HOST', 'localhost'),
        'db.driver'                        => env('DB_DRVER', 'pdo_mysql'),
        \Doctrine\DBAL\Connection::class   => function (ContainerInterface $container) {
            return DriverManager::getConnection([
                'dbname'       => $container->get('db.name'),
                'user'         => $container->get('db.user'),
                'password'     => $container->get('db.pass'),
                'host'         => $container->get('db.host'),
                'driver'       => $container->get('db.driver'),
            ]);
        },
        Database::class     => object()->constructor(get(\Doctrine\DBAL\Connection::class)),
        AspectKernel::class => function (ContainerInterface $container) {
            $applicationKernel = \TuxBoy\ApplicationApsect::getInstance();
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
        'twig.path'       => \DI\add([string('{basepath}/res/views')]),
        'twig.extensions' => [
                get(RouterTwigExtension::class),
                get(FlashExtension::class),
                get(FormExtension::class)
        ],
        'annotations' => add([
            Set::class,
						Length::class,
						Option::class
        ]),
        Std::class                       => object(),
        GroupCountBased::class           => object(),
        \FastRoute\RouteCollector::class => object()->constructor(get(Std::class), get(GroupCountBased::class)),
        Router::class                    => object()->constructor(get(\FastRoute\RouteCollector::class)),
        Twig_Environment::class          => factory(TwigFactory::class),
        AspectContainer::class           => object(GoAspectContainer::class),
        'goaop.aspect'                   => [
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
