<?php

namespace Core\Builder;

use Core\Concern\Current;
use Core\Plugin\Plugin;
use Core\Priority;
use ReflectionClass;

/**
 * Class Builder.
 */
class Builder
{
    use Current;

    /**
     * Permet de créer une nouvelle instance avec les dépendances défini s'il y en a.
     *
     * @param string $class_name
     * @param array  $arguments
     *
     * @return object
     */
    public static function create(string $class_name, array $arguments = [])
    {
        return !empty($arguments)
            ? self::current()->newInstanceArgs($class_name, $arguments)
            : self::current()->newInstance($class_name);
    }

    /**
     * @param string $class_name
     *
     * @return mixed
     */
    public function newInstance(string $class_name)
    {
        $class_name = $this->replacementClassName($class_name);

        return new $class_name();
    }

    /**
     * @param string $class_name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function newInstanceArgs(string $class_name, array $arguments = [])
    {
        $class_name = $this->replacementClassName($class_name);

        return (new ReflectionClass($class_name))->newInstanceArgs($arguments);
    }

    /**
     * @param string $class_name
     *
     * @return string
     */
    private function replacementClassName(string $class_name)
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
        $source = ClassBuilder::build($class_name, $traits);

        return $source;
    }
}
