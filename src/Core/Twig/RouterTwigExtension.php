<?php

namespace Core\Twig;

use Core\Router\Router;

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
     *
     * @return string
     */
    public function pathFor(string $name): string
    {
        return $this->router->getUrl($name);
    }
}
