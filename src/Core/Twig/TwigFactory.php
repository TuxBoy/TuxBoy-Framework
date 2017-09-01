<?php

namespace Core\Twig;

use Psr\Container\ContainerInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class TwigFactory.
 */
class TwigFactory
{
    /**
     * Factory afin de configurer Twig pour PHP-DI.
     *
     * @param ContainerInterface $container
     *
     * @return Twig_Environment
     */
    public function __invoke(ContainerInterface $container)
    {
        $loader = new Twig_Loader_Filesystem($container->get('twig.path'));
        $twig = new Twig_Environment($loader);
        foreach ($container->get('twig.extensions') as $extension) {
            $twig->addExtension($extension);
        }

        return $twig;
    }
}
