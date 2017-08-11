<?php
namespace Core;

use ReflectionClass;
use ReflectionMethod;

class Reflection_class extends ReflectionClass
{

	private $sub_methods = [];

	/**
	 * @param string $class_name
	 * @param ReflectionMethod $method
	 */
	public function addMethods(string $class_name, ReflectionMethod $method)
	{
		$sub_method = new ReflectionMethod($class_name, $method->getName());
		if (!in_array($sub_method, $this->sub_methods)) {
			$this->sub_methods[] = $sub_method;
		}
	}

	public function getMethods($filter = -1)
	{
		$methods = parent::getMethods($filter);
		foreach ($methods as $key => $method) {
			foreach ($this->sub_methods as $sub_method) {
				$methods[] = $sub_method;
			}
		}
		return $methods;
	}

}
