<?php
namespace App\User\Form;

use TuxBoy\Controller\Http;
use TuxBoy\Form\Builder\FormBuilder;
use TuxBoy\Form\Builder\FormHelper;
use TuxBoy\Router\Router;

/**
 * Class UserForm
 */
class UserForm extends FormHelper
{
    /**
     * @var FormBuilder
     */
    public $formBuilder;

    /**
     * @var Router
     */
    private $router;

    /**
     * UserForm constructor.
     * @param FormBuilder $formBuilder
     * @param Router $router
     */
    public function __construct(FormBuilder $formBuilder, Router $router)
    {
        $this->router = $router;
        $this->formBuilder = $formBuilder;
    }


    /**
     * @return FormBuilder
     */
    public function formLogin(): FormBuilder
    {
        return $this->formBuilder
            ->openForm($this->router->generateUri('user.login'), Http::POST)
            ->add($this->input('username', 'Login'))
            ->add($this->input('password', 'Mot de passe', 'password'))
            ->add($this->button('Se connecter'));
    }
}