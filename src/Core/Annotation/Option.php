<?php
namespace TuxBoy\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Type
 *
 * @Annotation
 * @Annotation\Target("PROPERTY")
 */
class Option extends Annotation
{

	/**
	 * Le type du champ (email, password)
	 *
	 * @var string
	 */
	public $type;

	/**
	 * True si le champ est obligatoire on pas.
	 *
	 * @var bool
	 */
	public $mendatory = true;

}
