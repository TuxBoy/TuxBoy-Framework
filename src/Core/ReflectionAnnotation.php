<?php

namespace Core;

use Exception;

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
	 * @param string $docComment
	 */
	public function __construct(string $docComment)
	{
		$this->docComment = $docComment;
	}

	/**
	 * Récupère l'annotation de la proriété demandé
	 *
	 * @param string $annotationName
	 * @return ReflectionAnnotation
	 * @throws Exception
	 */
	public function getAnnotation(string $annotationName): self
	{
		list($name, $value) = $this->parseDocComment($annotationName);
		$this->value = $value;
		$this->name = str_replace('@', '', $name);
		return $this;
	}

	/**
	 * Vérifie si l'annotation passé en paramètre existe.
	 *
	 * @param string $annotationName
	 * @return bool
	 */
	public function hasAnnotation(string $annotationName): bool
	{
		$this->parseDocComment($annotationName);
		return boolval($this->name);
	}

	/**
	 * Parse la phpdoc pour y extraire le nom de l'annotation de la proriété en question et sa
	 * valeur (facultatif)
	 *
	 * @param string $annotationName
	 * @return array|null
	 * @throws Exception
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
			return preg_match('#@'. $annotationName .'.+#', $item);
		}));
		$getAnnotation = current(array_filter($annotations, function ($item) use ($annotationName) {
			return preg_match('#@'. $annotationName .'#', $item);
		}));
		$name = null;
		$value = null;
		if ($getAnnotationValue) {
			list($name, $value) = explode(' ', $getAnnotationValue);
		} elseif ($getAnnotation) {
			list($name) = explode(' ', $getAnnotation);
		}
		return [$name, $value];
	}

	/**
	 * Récupère la valeur de l'anotation s'il y en a une
	 * @return string
	 */
	public function getValue(): ?string
	{
		return $this->value;
	}

	/**
	 * Récupère le nom de l'annotation
	 *
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}
}
