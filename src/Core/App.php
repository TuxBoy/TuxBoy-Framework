<?php
namespace Core;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\Dispatcher\GroupCountBased as FastDispatcher;
use FastRoute\RouteParser\Std;
use FastRoute\Dispatcher;
use DI\ContainerBuilder;
use FastRoute\RouteCollector;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class App
 * @package Core
 */
class App
{

	/**
	 * @var array
	 */
	private $config;

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var \Psr\Http\Message\ServerRequestInterface
	 */
	private $request;

  /**
   * @var RouteCollector
   */
	private $route;

  /**
   * @param ServerRequestInterface $request
   * @param array $config
   */
	public function __construct(ServerRequestInterface $request, array $config)
	{
	    $this->request = $request;
		$this->config  = $config;

        Plugin::current()->addBuilder(Priority::CORE, $this->config[Priority::CORE]);

        /** @var RouteCollector $routeCollector */
        $this->route = new RouteCollector(
            new Std(), new GroupCountBased()
        );
		$container_builder = new ContainerBuilder;
		$container_builder->addDefinitions($this->config[Priority::APP]);
		$this->container = $container_builder->build();
	}

  /**
   * @param string $route
   * @param callable $handle
   */
	public function get(string $route, callable $handle)
	{
		$this->route->get($route, $handle);
	}

    /**
     * @return FastDispatcher
     */
	public function getDispatcher(): FastDispatcher
	{
        return new FastDispatcher($this->route->getData());
	}

  /**
   * @return mixed|ResponseInterface
   */
	public function run() : ResponseInterface
	{
        $request_method = $this->request->getMethod();
        $path_uri       = $this->request->getUri()->getPath();
        $route          = $this->getDispatcher()->dispatch($request_method, $path_uri);
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
                $parameters[] = [
                  'request' => $this->request
                ];
                $response = $this->container->call($controller, $parameters);
                if (is_string($response)) {
                  return new Response(200, [], $response);
                }
                elseif ($response instanceof ResponseInterface) {
                  return $response;
                }
                else {
                  throw new Exception('The response is not a string or an instance of ResponseInterface');
                }
				break;
		}
	}

}
