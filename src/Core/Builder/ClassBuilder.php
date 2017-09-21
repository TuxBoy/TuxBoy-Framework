<?php

namespace TuxBoy\Builder;

use ReflectionClass;
use TuxBoy\Concern\Current;

/**
 * Class Class_Builder.
 */
class ClassBuilder
{
    use Current;

    /**
     * @param string $class_name
     * @param array  $dependencies
     *
     * @return string
     */
    public function build(string $class_name, array $dependencies = []): string
    {
        $traits = [];
        // Je parcours chaque trait des dépendances récupéré :
        foreach ($dependencies[$class_name] as $dependency) {
            $object = new ReflectionClass($dependency);
            if ($object->isTrait()) {
                $traits[] = $object->getName();
            }
        }

        return $this->buildClass($class_name, $traits);
    }

    /**
     * @param string $class_name
     * @param array  $traits
     *
     * @return string
     */
    public function buildClass(string $class_name, array $traits): string
    {
        // On génère un namespace unique pour chaque built
        $namespace = 'TuxBoy\\Application\\Built' . uniqid();
        $short_class = Namespaces::shortClassName($class_name);
        $traits_names = !empty($traits) ? implode("; \n \t use \\", $traits) : '';
        $built_class = $namespace . '\\' . $short_class;
        $source = 'namespace ' . $namespace . "; \n class " . $short_class . " extends \\$class_name { \n"
            . ($traits_names ? "\t use \\" . $traits_names . ';' : '')
            . "\n}";
        eval($source);

        return $built_class;
    }
}
