<?php

namespace Core;

use Core\Handler\HandlerInterface;
use Core\Plugin\Plugin;
use Core\Router\Router;
use DI\ContainerBuilder;
use Exception;
use FastRoute\Dispatcher;
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
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $container_builder = new ContainerBuilder();
        $default_config = require __DIR__ . '/config.php';
        $container_builder->addDefinitions($default_config[Priority::APP]);

        if ((!empty($default_config) && !empty($default_config[Priority::PLUGIN])) ||
            (!empty($config) && !empty($config[Priority::PLUGIN]))
        ) {
            $config = array_merge($default_config[Priority::PLUGIN], $config[Priority::PLUGIN]);
            Plugin::current()->addPlugin($config);
        }

        if ((!empty($default_config) && !empty($default_config[Priority::CORE])) ||
            (!empty($config) && !empty($config[Priority::CORE]))
        ) {
            $config = array_merge($default_config[Priority::CORE], $config[Priority::CORE]);
            Plugin::current()->addBuilder(Priority::CORE, $config[Priority::CORE]);
        } elseif (!empty($config) && !empty($config[Priority::APP])) {
            $container_builder->addDefinitions($config[Priority::APP]);
        }
        $this->container = $container_builder->build();
        // Active le handle pour afficher les erreurs
        $this->container->get(HandlerInterface::class)->handle();
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
