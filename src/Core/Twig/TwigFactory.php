<?php

namespace TuxBoy\Twig;

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
        $loader = new Twig_Loader_Filesystem();
        $twig = new Twig_Environment($loader);
        $paths = $container->get('twig.path');
        foreach ($container->get('twig.extensions') as $extension) {
            $twig->addExtension($extension);
        }
        foreach ($paths as $namespace => $path) {
            if (is_string($namespace)) {
                $loader->addPath($path, $namespace);
            } else {
                $loader->addPath($path);
            }
        }

        return $twig;
    }
}
