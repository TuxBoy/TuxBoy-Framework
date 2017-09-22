<?php
namespace TuxBoy\Form\Builder;

use Doctrine\DBAL\Types\Type;
use TuxBoy\Annotation\Option;
use TuxBoy\Entity;
use TuxBoy\Form\Button;
use TuxBoy\Form\Input;
use TuxBoy\Form\Textarea;
use TuxBoy\ReflectionAnnotation;

/**
 * Class EntityFormBuilder
 */
class EntityFormBuilder
{

	/**
	 * @var FormBuilder
	 */
	private $formBuilder;

	/**
	 * EntityFormBuilder constructor.
	 *
	 * @param FormBuilder $formBuilder
	 */
	public function __construct(FormBuilder $formBuilder)
	{
		$this->formBuilder = $formBuilder;
	}

	/**
	 * Génère un formulaire à partir d'une entity.
	 *
	 * @param Entity $entity
	 * @param string|null $action
	 * @return string
	 */
	public function generateForm(Entity $entity, ?string $action = null): string
	{
		// En gros ça va lire toutes les proriétés de l'entity afin de construire le formulaire
		if (is_null($action)) {
			$action = '#';
		}
		$this->formBuilder->openForm($action, 'POST');
		foreach (array_keys(get_object_vars($entity)) as $property) {
			$propertyAnnotation = new ReflectionAnnotation($entity, $property);
			if (
				$propertyAnnotation->hasAnnotation('var')
				&& $propertyAnnotation->getAnnotation('var')->getValue() === Type::STRING
			) {
				$value = null;
				if (property_exists(get_class($entity), $property) && $entity->$property) {
					$value = $entity->$property;
				}
				$input = new Input($property, $value);
				if ($propertyAnnotation->getPropertyAnnotation(Option::class)) {
					$optionAnnoation = $propertyAnnotation->getPropertyAnnotation(Option::class);
					$type = $optionAnnoation->type ? $optionAnnoation->type : Type::TEXT;
					if ($optionAnnoation->mendatory) {
						$input->setAttribute('required');
					}
				}
				else {
					$type = Type::TEXT;
				}
				$input->setAttribute('type', $type);
				$this->formBuilder->add($input);
			}

			if (
				$propertyAnnotation->hasAnnotation('var')
				&& $propertyAnnotation->getAnnotation('var')->getValue() === Type::TEXT
			) {
				$value = null;
				if (property_exists(get_class($entity), $property) && $entity->$property) {
					$value = $entity->$property;
				}
				$this->formBuilder->add((new Textarea($property, $value)));
			}
		}
		$this->formBuilder->add((new Button('Envoyer'))->setAttribute('type', 'submit'));
		return $this->formBuilder->build();
	}

}
