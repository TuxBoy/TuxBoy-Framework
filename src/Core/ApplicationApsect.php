<?php

namespace Core;

use Core\Plugin\Plugin;
use Go\Core\AspectContainer;
use Go\Core\AspectKernel;

/**
 * ApplicationApsect.
 *
 * Register all aspect plugins defined in the config file.
 */
class ApplicationApsect extends AspectKernel
{
    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts.
     *
     * @param AspectContainer $container
     */
    protected function configureAop(AspectContainer $container)
    {
        // Get all aspect plugins for enabled.
        $plugins = Plugin::current()->getAspectPlugins();
        foreach ($plugins as $plugin) {
            $container->registerAspect($plugin);
        }
    }
}
