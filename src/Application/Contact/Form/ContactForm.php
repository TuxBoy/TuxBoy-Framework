<?php

namespace App\Contact\Form;

use TuxBoy\Form\Builder\FormBuilder;
use TuxBoy\Form\Button;
use TuxBoy\Form\Input;
use TuxBoy\Form\Textarea;
use TuxBoy\Router\Router;

class ContactForm
{
    /**
     * @var FormBuilder
     */
    public $formBuilder;

    public function __construct(FormBuilder $formBuilder, Router $router)
    {
        $this->formBuilder = $formBuilder
            ->openForm($router->generateUri('contact.index'), 'POST')
            ->add((new Input('name'))->setAttribute('placeholder', 'Nom'))
            ->add((new Textarea('content'))->setAttribute('placeholder', 'Contenu'))
            ->add((new Button('Envoyer'))->setAttribute('class', 'waves-effect waves-light btn'));
    }
}
