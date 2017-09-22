<?php

namespace TuxBoy;

use Doctrine\Common\Annotations\AnnotationReader;
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
     * @var ReflectionClass
     */
    private $argument;

	/**
	 * @var null|string
	 */
	private $propertyName;

	/**
     * ReflectionAnnotation constructor.
     *
     * @param string|object $argument      Le nom de la classe ou l'object que l'on souhaite récupérer les annotations
     * @param string|null   $propertyName
     */
    public function __construct($argument, string $propertyName = null)
    {
        $this->argument = new ReflectionClass($argument);
				$this->propertyName = $propertyName;
			// Si le property_name est null, alors on souhaite obtenir les annotations de la classe
        $this->docComment = null === $this->propertyName
            ? $this->argument->getDocComment()
            : $this->argument->getProperty($this->propertyName)->getDocComment();
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

    /**
     * @param string $annotationName
     *
     * @return null|object
     */
    public function getClassAnnotation(string $annotationName)
    {
        return (new AnnotationReader())->getClassAnnotation($this->argument, $annotationName);
    }

	/**
	 * @param string $annotationName
	 * @return null|object
	 */
    public function getPropertyAnnotation(string $annotationName)
		{
			return (new AnnotationReader)->getPropertyAnnotation(
				new \ReflectionProperty($this->argument->getName(), $this->propertyName), $annotationName
			);
		}
}
