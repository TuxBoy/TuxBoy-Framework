<?php

namespace TuxBoy\Plugin;

use Go\Aop\Aspect;
use TuxBoy\Builder\Builder;
use TuxBoy\Concern\Current;
use TuxBoy\Exception\PluginException;
use TuxBoy\Priority;

/**
 * Class Plugin.
 */
class Plugin
{
    use Current;

    /**
     * @var array
     */
    private $builders = [];

    /**
     * @var array
     */
    private $plugins = [];

    /**
     * @param string $key
     * @param        $builders array
     */
    public function addBuilder(string $key, array $builders): void
    {
        $this->builders[$key] = $builders;
    }

    /**
     * @param array $plugins
     */
    public function addPlugin(array $plugins): void
    {
        foreach ($plugins as $key => $plugin) {
            if (is_array($plugin)) {
                $this->plugins[Priority::PLUGIN][$key] = $plugin;
            } else {
                $this->plugins[Priority::PLUGIN][$plugin] = [];
            }
        }
    }

    /**
     * @param string $key
     *
     * @throws \Exception
     *
     * @return array|null
     */
    public function getBuilders(string $key): ?array
    {
        if (!$this->hasBuilder($key)) {
            return null;
        }

        return $this->builders[$key];
    }

    /**
     * @param string $class_name
     *
     * @throws PluginException
     *
     * @return string
     */
    public function getPlugin(string $class_name): string
    {
        $plugins = array_keys($this->plugins[Priority::PLUGIN]);

        return current(array_filter(
            $plugins,
            function ($plugin) use ($class_name) {
                return $plugin === $class_name;
            }
        ));
    }

    /**
     * Récupère tous les plugins, s'il n'y a pas de plugin, retourne un tableau vide.
     *
     * @throws PluginException
     *
     * @return array
     */
    public function getPlugins(): array
    {
        return (isset($this->plugins[Priority::PLUGIN]) && !empty($this->plugins[Priority::PLUGIN]))
            ? $this->plugins[Priority::PLUGIN]
            : [];
    }

    /**
     * Récupère les plugin qui implement Aspect définis dans Priority::PLUGIN et les instancies.
     *
     * @example Dans le fichier config.php => Priority::PLUGIN => [\App\TestAspect::class]
     *
     * @return array Un tableau d'object des plugin pour gérer l'AOP
     */
    public function getAspectPlugins(): array
    {
        $aspect_plugins = [];
        foreach ($this->getPlugins() as $plugin_name => $configuration) {
            if (!empty($configuration)) {
                $object = Builder::create($plugin_name, [$configuration]);
            } else {
                $object = Builder::create($plugin_name);
            }
            if ($object instanceof Aspect) {
                $aspect_plugins[] = $object;
            }
        }

        return $aspect_plugins;
    }

    /**
     * @param $key  string
     *
     * @return bool
     */
    public function hasBuilder(string $key): bool
    {
        return array_key_exists($key, $this->builders);
    }

    /**
     * @param $class_name
     *
     * @return null|PluginInterface
     */
    public function get($class_name): ?PluginInterface
    {
        $plugin = null;
        $class_name = is_string($class_name) ? $class_name : get_class($class_name);
        $plugin = self::current()->getPlugin($class_name);
        if ($plugin) {
            $plugin = Builder::create($plugin);
        }

        return $plugin;
    }
}
