<?php
namespace App\Admin\Twig;

use Psr\Container\ContainerInterface;

class MenuExtension extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('links', [$this, 'getLinkMenu'])
        ];
    }

    /**
     * @return string
     */
    public function getLinkMenu()
    {
        $output = 'azezaaze';
        foreach ($this->container->get('entities') as $entity) {
            $output .= '<a href="">'. $entity .'</a>';
        }
        return $output;
    }
}
