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
	 * @return array|null
	 * @throws \Exception
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
	 * @return array
	 * @throws Plugin_Exception
	 */
	public function getPlugin(string $class_name)
	{
		if (empty($this->plugins[Priority::PLUGIN])) {
			throw new Plugin_Exception("Not plugin");
		}
		foreach ($this->plugins[Priority::PLUGIN] as $plugin) {
			if ($class_name === $plugin) {
				return $plugin;
			}
		}
		return null;
	}

	/**
	 * @return array
	 * @throws Plugin_Exception
	 */
	public function getPlugins() : array
	{
		if (empty($this->plugins[Priority::PLUGIN])) {
			throw new Plugin_Exception("Not plugin");
		}
		return $this->plugins[Priority::PLUGIN];
	}

	/**
	 * @param $key  string
	 * @return bool
	 */
	public function hasBuilder(string $key): bool
	{
		return array_key_exists($key, $this->builders);
	}

	/**
	 * @param $class_name
	 * @return null|PluginInterface
	 */
	public function get($class_name)
	{
		$plugin  = null;
		$plugin = Plugin::current()->getPlugin(get_class($class_name));
		if ($plugin) {
			$plugin = Builder::create($plugin);
		}
		return $plugin;
	}

}
