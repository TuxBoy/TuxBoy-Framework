<?php

namespace TuxBoy\Router;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;

/**
 * Class Router.
 */
class Router
{
    /**
     * @var FastRouteRouter
     */
    private $router;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * @param string   $path
     * @param callable $callable
     * @param string   $name
     */
    public function get(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, $callable, ['GET'], $name));
    }

    /**
     * @param string   $path
     * @param callable $callable
     * @param string   $name
     */
    public function post(string $path, $callable, ?string $name = null)
    {
        $this->router->addRoute(new ZendRoute($path, $callable, ['POST'], $name));
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return Route
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);

        return $result->isSuccess() ? new Route(
            $result->getMatchedRouteName(),
            $result->getMatchedMiddleware(),
            $result->getMatchedParams()
        ) : null;
    }

    /**
     * @param string $name
     * @param array  $params
     *
     * @return null|string
     */
    public function generateUri(string $name, array $params = []): ?string
    {
        return $this->router->generateUri($name, $params);
    }
}
