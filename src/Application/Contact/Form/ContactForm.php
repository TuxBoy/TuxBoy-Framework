<?php
namespace App\Contact\Form;

use Core\Form\Builder\FormBuilder;
use Core\Router\Router;
use Core\Form\Button;
use Core\Form\Input;
use Core\Form\Textarea;
use Psr\Http\Message\ServerRequestInterface;

class ContactForm
{

	/**
	 * @var FormBuilder
	 */
	public $formBuilder;

	public function __construct(FormBuilder $formBuilder, Router $router, ServerRequestInterface $request)
	{
		dump($request->getParsedBody());
		$this->formBuilder = $formBuilder
			->openForm($router->generateUri('contact.index'), 'POST')
			->add((new Input('name'))->setAttribute('placeholder', 'Nom'))
			->add((new Textarea('content'))->setAttribute('placeholder', 'Contenu'))
			->add((new Button('Envoyer'))->setAttribute('class', 'waves-effect waves-light btn'));
	}

}
