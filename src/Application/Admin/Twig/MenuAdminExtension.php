<?php
namespace App\Admin\Twig;

use Psr\Container\ContainerInterface;
use TuxBoy\Router\Router;

class MenuAdminExtension extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    private $container;

        /**
         * @var Router
         */
    private $router;

    public function __construct(ContainerInterface $container, Router $router)
    {
        $this->container = $container;
                $this->router = $router;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('links', [$this, 'getLinkMenu'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @return string
     */
    public function getLinkMenu()
    {
        $output = '';
        if ($this->container->has('menu.admin')) {
            foreach ($this->container->get('menu.admin') as $menu) {
                $output .= $menu->build($this->router);
                ;
            }
        }
        return $output;
    }
}
