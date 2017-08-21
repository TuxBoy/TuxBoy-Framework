<?php

namespace Core\Twig;

use Core\Router\Router;
use Twig_SimpleFunction;

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

    public function getFunctions()
    {
        return [
           new Twig_SimpleFunction('path_for', [$this, 'pathFor'])
        ];
    }

    public function pathFor(string $name): string
    {
        return $this->router->getUrl($name);
    }
}
