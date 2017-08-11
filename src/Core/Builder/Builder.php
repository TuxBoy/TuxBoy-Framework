<?php
namespace Core\Builder;

use Core\Concern\Current;
use Core\Plugin;
use Core\Priority;
use Core\Reflection_class;
use ReflectionClass;

/**
 * Class Builder
 * @package Core
 */
class Builder
{
	use Current;

	/**
	 * Permet de créer une nouvelle instance avec les dépendances défini s'il y en a
	 *
	 * @param string $class_name
	 * @param array  $arguments
	 * @return object
	 */
	public function create(string $class_name, array $arguments = [])
	{
		return !empty($arguments)
			? self::current()->newInstanceArgs($class_name, $arguments)
			: self::current()->newInstance($class_name);
	}

	/**
	 * @param string $class_name
	 * @return mixed
	 */
	private function newInstance(string $class_name)
	{
		$class_name = $this->replacementClassName($class_name);
		return new $class_name();
	}

	/**
	 * @param string $class_name
	 * @param array  $arguments
	 * @return mixed
	 */
	private function newInstanceArgs(string $class_name, array $arguments = [])
	{
		$class_name = $this->replacementClassName($class_name);
		return (new Reflection_class($class_name))->newInstanceArgs($arguments);
	}

	/**
	 * @param string      $class_name
	 * @return string
	 */
	private function replacementClassName(string $class_name): string
	{
		$dependencies = Plugin::current()->getBuilders(Priority::CORE);
		if (!isset($dependencies[$class_name])) {
			return $class_name;
		}
		$traits = [];
		// Je parcours chaque trait des dépendances récupéré :
		foreach ($dependencies[$class_name] as $dependency) {
			$object = new ReflectionClass($dependency);
			if ($object->isTrait()) {
				$traits[] = $object->getName();
			}
		}
		$source = Class_Builder::build($class_name, $traits);
		return $source;
	}

}