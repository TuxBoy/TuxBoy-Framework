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
    public static function build(string $class_name, array $traits)
    {
        $namespace = self::getNamespace($class_name) . '\\Built';
        $short_class = self::shortClassName($class_name);
        $traits_names = !empty($traits) ? implode("; \n \t use \\", $traits) : '';
        $built_class = $namespace . '\\' . $short_class;
        $source = 'namespace ' . $namespace . "; \n class " . $short_class . " extends \\$class_name { \n"
            . ($traits_names ? "\t use \\" . $traits_names . ';' : '')
             . "\n}";
        eval($source);

        return $built_class;
    }

    /**
     * Récupère le namespace sans le nom de la classe.
     *
     * @param string $class_name
     *
     * @return string
     */
    public static function getNamespace(string $class_name)
    {
        // Calcul le nombre de caractère avant le dernier \
        if ($i = mb_strrpos($class_name, '\\')) {
            return mb_substr($class_name, 0, $i);
        }

        return '';
    }

    /**
     * Récupère le nom de la classe sans le namespace.
     *
     * @param string $class_name
     *
     * @return string
     */
    public static function shortClassName(string $class_name)
    {
        $i = mb_strrpos($class_name, '\\');
        // On a un antislash du coup on prend ce qu'il y a après
        if ($i !== false) {
            $class_name = mb_substr($class_name, $i + 1);
        }

        return $class_name;
    }
}
