<?php
namespace App\Contact\Controller;

use App\Contact\Form\ContactForm;
use TuxBoy\Controller\Controller;
use TuxBoy\Router\Router;
use GuzzleHttp\Psr7\ServerRequest;

class ContactController extends Controller
{

	public function index(ServerRequest $request, ContactForm $contactForm, Router $router)
	{
		if ($request->getMethod() === 'POST') {
			// TODO Faire la validation et l'envoi de mail.
			return $this->redirectTo($router->generateUri('/contact'));
		}
		return $this->view->render('@contact/index.twig', ['formBuilder' => $contactForm->formBuilder]);
	}

}
