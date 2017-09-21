<?php

namespace TuxBoy;

use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Exception;
use Go\Core\AspectKernel;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TuxBoy\Builder\Builder;
use TuxBoy\Exception\NotMatchRouteException;
use TuxBoy\Handler\HandlerInterface;
use TuxBoy\Plugin\Plugin;
use TuxBoy\Router\Router;

/**
 * Class App.
 */
class App
{
    /**
     * @var ContainerInterface
     */
    public $container;

    /**
     * @var Router
     */
    public $router;

    /**
     * @var Application[]
     */
    private $applications;

    /**
     * @param array $custom_config
     * @param array $applications
     */
    public function __construct(array $custom_config = [], array $applications = [])
    {
        $this->initApplications($applications);
        $this->container_builder = new ContainerBuilder();
        $default_config = require __DIR__ . '/config.php';
        $this->container_builder->addDefinitions($default_config[Priority::APP]);
        $this->addPluginConfig($custom_config[Priority::PLUGIN], $default_config[Priority::PLUGIN]);
        $this->addCoreConfig($custom_config[Priority::CORE], $default_config[Priority::CORE]);

        $this->initApplicationConfig();
        if (!empty($custom_config) && !empty($custom_config[Priority::APP])) {
            $this->container_builder->addDefinitions($custom_config[Priority::APP]);
        }
        $this->container = $this->container_builder->build();

        $kernel = $this->container->get(AspectKernel::class);
        // Active le handle pour afficher les erreurs
        $this->container->get(HandlerInterface::class)->handle();

        foreach ($this->getContainer()->get('annotations') as $annotation) {
            AnnotationRegistry::loadAnnotationClass($annotation);
        }
        // Il faudra réfactorer la partie GoAOP dans PHP-DI
        $aspectContainer = $kernel->getContainer();
        $aspects = $this->getContainer()->get('goaop.aspect');
        foreach ($aspects as $aspect) {
            $aspectContainer->registerAspect($aspect);
        }
        ConnectionManager::setConfig('default', [
            'className'     => Connection::class,
            'driver'        => 'Cake\Database\Driver\Mysql',
            'database'      => $this->getContainer()->get('db.name'),
            'username'      => $this->getContainer()->get('db.user'),
            'password'      => $this->getContainer()->get('db.pass'),
            'encoding'      => 'utf8',
            'timezone'      => 'UTC',
            'cacheMetadata' => false // If set to `true` you need to install the optional "cakephp/cache" package.
        ]);

        $this->initApplicationsRoutes($this->getContainer()->get(Router::class));
    }

    /**
     * Ajoute la définition des DI de l'application.
     */
    private function initApplicationConfig(): void
    {
        foreach ($this->applications as $application) {
            $this->container_builder->addDefinitions($application->addConfig());
        }
    }

    /**
     * Ajoute les routes définies dans l'application.
     *
     * @param $router Router
     */
    private function initApplicationsRoutes(Router $router): void
    {
        foreach ($this->applications as $application) {
            $application->getRoutes($router);
        }
    }

    /**
     * Initialisize les nouvelles applications.
     *
     * @param array $applications
     */
    private function initApplications(array $applications = []): void
    {
        foreach ($applications as $application) {
            /* @var $application Application */
            $this->applications[] = Builder::create($application);
        }
    }

    /**
     * @param array $custom_config
     * @param array $default_config
     */
    public function addPluginConfig(array $custom_config = [], array $default_config = []): void
    {
        if (!empty($custom_config)) {
            $custom_config = array_merge($default_config, $custom_config);
            Plugin::current()->addPlugin($custom_config);
        } else {
            Plugin::current()->addPlugin($default_config);
        }
    }

    /**
     * @param array $config
     * @param array $default_config
     */
    public function addCoreConfig(array $config, array $default_config): void
    {
        if (!empty($config)) {
            $config = array_merge($config, $default_config);
            Plugin::current()->addBuilder(Priority::CORE, $config);
        } else {
            Plugin::current()->addBuilder(Priority::CORE, $default_config);
        }
    }

    /**
     * @todo Cette méthode a besoin d'un bon réfactoring
     *
     * @param ServerRequestInterface $request
     *
     * @throws Exception
     *
     * @return mixed|ResponseInterface
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $router = $this->container->get(Router::class);
        $route = $router->match($request);
        if (null === $route) {
            throw new NotMatchRouteException();
        }
        $parameters = array_merge($route->getParams(), ['request' => $request]);
        $response = $this->container->call($route->getCallback(), $parameters);
        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        }
        throw new Exception('The response is not a string or an instance of ResponseInterface');
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
