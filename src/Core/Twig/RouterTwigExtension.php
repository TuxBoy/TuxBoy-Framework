<?php

namespace TuxBoy\Twig;

use TuxBoy\Router\Router;

class RouterTwigExtension extends \Twig_Extension
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
           new \Twig_SimpleFunction('path', [$this, 'pathFor'])
        ];
    }

    /**
     * @param string $name
     * @param array  $params
     *
     * @return string|null
     */
    public function pathFor(string $name, array $params = []): ?string
    {
        return $this->router->generateUri($name, $params);
    }
}
