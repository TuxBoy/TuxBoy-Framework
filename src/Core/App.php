<?php

namespace Core;

use Core\Concern\Current;
use Core\Handler\HandlerInterface;
use Core\Plugin\Plugin;
use Core\Router\Router;
use DI\ContainerBuilder;
use Exception;
use FastRoute\Dispatcher;
use Go\Core\AspectKernel;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * @var array
     */
    private $applications;

    /**
     * @param array $custom_config
     * @param array $applications
     */
    public function __construct(array $custom_config = [], array $applications = [])
    {
        $container_builder = new ContainerBuilder();
        $default_config = require __DIR__ . '/config.php';
        $container_builder->addDefinitions($default_config[Priority::APP]);
        $this->addPluginConfig($custom_config[Priority::PLUGIN], $default_config[Priority::PLUGIN]);
        $this->addCoreConfig($custom_config[Priority::CORE], $default_config[Priority::CORE]);

        if (!empty($custom_config) && !empty($custom_config[Priority::APP])) {
            $container_builder->addDefinitions($custom_config[Priority::APP]);
        }
        $this->container = $container_builder->build();

        $kernel = $this->container->get(AspectKernel::class);
        // Active le handle pour afficher les erreurs
        $this->container->get(HandlerInterface::class)->handle();

        // Il faudra réfactorer la partie GoAOP dans PHP-DI
        $aspectContainer = $kernel->getContainer();
        $aspects = $this->getContainer()->get('goaop.aspect');
        foreach ($aspects as $aspect) {
            $aspectContainer->registerAspect($aspect);
        }
        $this->applications = $applications;

        $this->initApplications();
    }

    private function initApplications()
    {
        foreach ($this->applications as $application) {
            $getFileRouter = str_replace('Application', '', $application);
            $getFileRouter = str_replace('App', '', $getFileRouter);
            $getFileRouter = str_replace('\\', '/', $getFileRouter);
            $app = $this;
            require dirname(__DIR__) . '/Application/' . $getFileRouter . 'routes.php';
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
