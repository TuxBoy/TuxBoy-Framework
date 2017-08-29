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
     * @param array $custom_config
     */
    public function __construct(array $custom_config = [])
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
        $request_method = $request->getMethod();
        $path_uri = $request->getUri()->getPath();
        $router = $this->container->get(Router::class);
        $route = $router->getDispatcher()->dispatch($request_method, $path_uri);

        switch ($route[0]) {
            case Dispatcher::NOT_FOUND:
                $response = new Response();
                $response->getBody()->write('Page Not Found.');

                return $response->withStatus(404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response = new Response();
                $response->getBody()->write('Page Not Allow.');

                return $response->withStatus(405);
                break;
            case Dispatcher::FOUND:
                $controller = $route[1];
                $parameters = $route[2];
                $parameters = array_merge($parameters, ['request' => $request]);
                $response = $this->container->call($controller, $parameters);
                if (is_string($response)) {
                    return new Response(200, [], $response);
                } elseif ($response instanceof ResponseInterface) {
                    return $response;
                }

                throw new Exception('The response is not a string or an instance of ResponseInterface');
                break;
        }

        return null;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
