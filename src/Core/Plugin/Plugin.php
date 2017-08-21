<?php

namespace Core\Plugin;

use Core\Builder\Builder;
use Core\Concern\Current;
use Core\Exception\Plugin_Exception;
use Core\Priority;

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
     * @param array $plugin
     */
    public function addPlugin(array $plugin): void
    {
        $this->plugins[Priority::PLUGIN] = $plugin;
    }

    /**
     * @param string $key
     *
     * @throws \Exception
     *
     * @return array|null
     */
    public function getBuilders(string $key)
    {
        if (!$this->hasBuilder($key)) {
            return null;
        }

        return $this->builders[$key];
    }

    /**
     * @param string $class_name
     *
     * @throws Plugin_Exception
     *
     * @return string
     */
    public function getPlugin(string $class_name)
    {
        if (empty($this->plugins[Priority::PLUGIN])) {
            throw new Plugin_Exception('Not plugin');
        }
        $plugin = array_filter($this->plugins[Priority::PLUGIN], function ($plugin) use ($class_name) {
            return $plugin === $class_name;
        });

        return current($plugin);
    }

    /**
     * @throws Plugin_Exception
     *
     * @return array
     */
    public function getPlugins(): array
    {
        if (empty($this->plugins[Priority::PLUGIN])) {
            throw new Plugin_Exception('Not plugin');
        }

        return $this->plugins[Priority::PLUGIN];
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
    public function get($class_name)
    {
        $plugin = null;
        $plugin = self::current()->getPlugin(get_class($class_name));
        if ($plugin) {
            $plugin = Builder::create($plugin);
        }

        return $plugin;
    }
}
