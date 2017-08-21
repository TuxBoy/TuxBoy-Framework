<?php

namespace Core\Router;

use FastRoute\Dispatcher\GroupCountBased as FastDispatcher;
use FastRoute\RouteCollector;

class Router extends RouteCollector
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @param string      $method
     * @param string      $route
     * @param callable    $handle
     * @param null|string $name
     *
     * @return Router
     */
    public function add($method, $route, $handle, ?string $name = null): self
    {
        $this->routes[$name] = $route;
        $this->addRoute($method, $route, $handle);

        return $this;
    }

    /**
     * @param string      $route
     * @param mixed       $handler
     * @param null|string $name
     *
     * @return Router
     */
    public function get($route, $handler, ?string $name = null)
    {
        $this->add('GET', $route, $handler, $name);

        return $this;
    }

    /**
     * @param string      $route
     * @param mixed       $handler
     * @param null|string $name
     *
     * @return Router
     */
    public function post($route, $handler, ?string $name = null): Router
    {
        $this->add('POST', $route, $handler, $name);

        return $this;
    }

    /**
     * @param string $name
     *
     * @throws RouterException
     *
     * @return string
     */
    public function getUrl(string $name): string
    {
        if (!array_key_exists($name, $this->routes)) {
            throw new RouterException('Aucune route ne correspond au nom donnée');
        }

        return $this->routes[$name];
    }

    /**
     * @return FastDispatcher
     */
    public function getDispatcher(): FastDispatcher
    {
        return new FastDispatcher($this->getData());
    }
}
