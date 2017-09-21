<?php

namespace TuxBoy\Builder;

use ReflectionClass;
use TuxBoy\Concern\Current;
use TuxBoy\Plugin\Plugin;
use TuxBoy\Priority;

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
    private function replacementClassName(string $class_name): string
    {
        $dependencies = Plugin::current()->getBuilders(Priority::CORE);
        if (!isset($dependencies[$class_name])) {
            return $class_name;
        }
        $class_builder = ClassBuilder::current();
        $source = $class_builder->build($class_name, $dependencies);

        return $source;
    }
}
