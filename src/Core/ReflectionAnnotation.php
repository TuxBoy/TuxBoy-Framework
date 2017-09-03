<?php

namespace Core;

use Exception;
use ReflectionClass;

/**
 * ReflectionAnnotation.
 */
class ReflectionAnnotation
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $docComment;

    /**
     * ReflectionAnnotation constructor.
     *
     * @param string|object $argument      Le nom de la classe ou l'object que l'on souhaite récupérer les annotations
     * @param string|null   $property_name
     */
    public function __construct($argument, string $property_name = null)
    {
        $argument = new ReflectionClass($argument);
        // Si le property_name est null, alors on souhaite obtenir les annotations de la classe
        $this->docComment = is_null($property_name)
            ? $argument->getDocComment()
            : $argument->getProperty($property_name)->getDocComment();
    }

    /**
     * Récupère l'annotation de la proriété demandé.
     *
     * @param string $annotationName
     *
     * @throws Exception
     *
     * @return ReflectionAnnotation
     */
    public function getAnnotation(string $annotationName): self
    {
        list($this->name, $this->value) = $this->parseDocComment($annotationName);

        return $this;
    }

    /**
     * Vérifie si l'annotation passé en paramètre existe.
     *
     * @param string $annotationName
     *
     * @return bool
     */
    public function hasAnnotation(string $annotationName): bool
    {
        return in_array($annotationName, $this->parseDocComment($annotationName), true);
    }

    /**
     * Parse la phpdoc pour y extraire le nom de l'annotation de la proriété en question et sa
     * valeur (facultatif).
     *
     * @param string $annotationName Le nom de l'annotation à parser
     *
     * @throws Exception
     *
     * @return array|null
     */
    private function parseDocComment(string $annotationName): ?array
    {
        $docComment = $this->docComment;
        $commentsDocs = array_filter(explode('*', $docComment), function ($annotation) {
            return !empty(trim($annotation));
        });
        $annotations = array_filter(array_map(function ($annotation) {
            return trim(trim($annotation, '/'));
        }, $commentsDocs), function ($item) {
            return preg_match('#@.+#', $item, $annotations);
        });
        $getAnnotationValue = current(array_filter($annotations, function ($item) use ($annotationName) {
            return preg_match('#@' . $annotationName . '.+#', $item);
        }));
        // L'annotation sans valeur @example @length
        $getAnnotation = current(array_filter($annotations, function ($item) use ($annotationName) {
            return preg_match('#@' . $annotationName . '#', $item);
        }));
        $name = null;
        $value = null;
        if ($getAnnotationValue) {
            list($name, $value) = explode(' ', $getAnnotationValue);
        } elseif ($getAnnotation) {
            list($name) = explode(' ', $getAnnotation);
        }

        return [str_replace('@', '', $name), $value];
    }

    /**
     * Récupère la valeur de l'anotation s'il y en a une.
     *
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Récupère le nom de l'annotation.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
