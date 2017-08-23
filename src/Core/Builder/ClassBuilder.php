<?php

namespace Core\Builder;

/**
 * Class Class_Builder.
 */
class ClassBuilder
{
    /**
     * @param string $class_name
     * @param array  $traits
     *
     * @return string
     */
    public static function build(string $class_name, array $traits): string
    {
    	static $built_class = null;
    	if (!isset($built_class)) {
			$namespace = self::getBuiltNameSpace($class_name);
			$short_class = Namespaces::shortClassName($class_name);
			$traits_names = !empty($traits) ? implode("; \n \t use \\", $traits) : '';
			$built_class = $namespace . '\\' . $short_class;
			$source = 'namespace ' . $namespace . "; \n class " . $short_class . " extends \\$class_name { \n"
				. ($traits_names ? "\t use \\" . $traits_names . ';' : '')
				. "\n}";
			// On peut sauvegarder en cache
			eval($source);
		}

        return $built_class;
    }

    //--------------------------------------------------------------------------------------- isBuilt
	/**
	 * Returns true if class name is a built class name
	 *
	 * A built class has a namespace beginning with 'Vendor\Application\Built\'
	 *
	 * @param $class_name string
	 * @return boolean
	 */
	public static function isBuilt($class_name)
	{

		$cache_file = dirname(dirname(dirname(__DIR__))) . '/cache/builder/dependencies';
		$built_class = file_get_contents($cache_file);
		$namespace = self::getBuiltNameSpace($class_name);
		return ($namespace = self::getBuiltNameSpace($built_class))
			? (substr($class_name, 0, strlen($namespace)) === $namespace)
			: false;
	}

	/**
	 * Returns the prefix namespace for built classes
	 *
	 * @return string|null
	 */
	public static function getBuiltNameSpace($class_name)
	{
		static $namespace = null;
		if (!isset($namespace)) {
			$namespace = Namespaces::getNamespace($class_name) . '\\Built\\';
		}
		return $namespace;
	}


}
