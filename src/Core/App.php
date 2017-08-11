<?php
namespace Core;

use function FastRoute\simpleDispatcher;
use FastRoute\Dispatcher;
use DI\ContainerBuilder;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;

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

	private $route;

	private $dispatcher;

	public function __construct(array $config)
	{
		$this->request = ServerRequest::fromGlobals();
		$this->config  = $config;

		$this->dispatcher = simpleDispatcher([$this, 'getRoute']);
		$container_builder = new ContainerBuilder;
		$container_builder->addDefinitions($this->config[Priority::APP]);
		$this->container = $container_builder->build();
	}

	public function get(string $route, callable $handle)
	{
		$this->route->get($route, $handle);
	}

	public function getRoute(RouteCollector $route)
	{
		// $this->route = new RouteCollector(new Std(), new GroupCountBased());
		$this->route = $route;
	}

	public function run()
	{
		$route =
			$this->dispatcher->dispatch($this->request->getMethod(), $this->request->getUri()->getPath());
		dump($route);
		switch ($route[0]) {
			case Dispatcher::NOT_FOUND:
				// ... 404 Not Found
				break;
			case Dispatcher::METHOD_NOT_ALLOWED:
				$allowedMethods = $route[1];
				// ... 405 Method Not Allowed
				break;
			case Dispatcher::FOUND:
				$response = new Response();
				$controller = $route[1];
				$parameters = $route[2];
				$parameters = [
					'request'  => $this->request,
					'response' => $response
				];
				return $this->container->call($controller, $parameters);
				break;
		}
	}

}
