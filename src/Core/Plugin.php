<?php
namespace Core;

use Core\Concern\Current;
use Core\Exception\Plugin_Exception;

class Plugin
{

	use Current;

	/**
	 * @var array
	 */
	private $builders;

	/**
	 * @param string $key
	 * @param        $builders array
	 */
	public function addBuilder(string $key, array $builders): void
	{
		$this->builders[$key] = $builders;
	}

	/**
	 * @param string $key
	 * @return mixed
	 * @throws \Exception
	 */
	public function getBuilders(string $key): array
	{
		if (!$this->hasBuilder($key)) {
			throw new Plugin_Exception("Not Builders for the current key");
		}
		return $this->builders[$key];
	}

	/**
	 * @param $key  string
	 * @return bool
	 */
	public function hasBuilder(string $key): bool
	{
		return array_key_exists($key, $this->builders);
	}

}
