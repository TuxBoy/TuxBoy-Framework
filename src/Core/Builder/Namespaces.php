<?php

namespace TuxBoy\Builder;

class Namespaces
{
    /**
     * Récupère le namespace sans le nom de la classe.
     *
     * @param string $class_name
     *
     * @return string
     */
    public static function getNamespace(string $class_name): string
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
    public static function shortClassName(string $class_name): string
    {
        $i = mb_strrpos($class_name, '\\');
        // On a un antislash du coup on prend ce qu'il y a après
        if ($i !== false) {
            $class_name = mb_substr($class_name, $i + 1);
        }

        return $class_name;
    }
}
