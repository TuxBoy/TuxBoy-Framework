<?php
namespace Core\Twig;

use Core\Form\Builder\FormBuilder;

class FormExtension extends \Twig_Extension
{

	/**
	 * @return \Twig_SimpleFunction[]
	 */
	public function getFunctions(): array
	{
		return [
			new \Twig_SimpleFunction('form', [$this, 'getForm'], ['is_safe' => ['html']])
		];
	}

	/**
	 * @param FormBuilder $formBuilder
	 * @return string
	 */
	public function getForm(FormBuilder $formBuilder): string
	{
		return (string) $formBuilder->build();
	}

}
