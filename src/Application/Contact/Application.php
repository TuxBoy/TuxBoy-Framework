<?php
namespace App\Contact;

use App\Contact\Controller\ContactController;
use TuxBoy\ApplicationInterface;
use TuxBoy\Router\Router;
use function DI\add;

class Application implements ApplicationInterface
{

	/**
	 * Définie les routes de l'application.
	 *
	 * @param Router $router
	 */
	public function getRoutes(Router $router): void
	{
		$router->get('/contact', [ContactController::class, 'index'], 'contact.index');
		$router->post('/contact', [ContactController::class, 'index']);
	}

	/**
	 * Pour ajouter la configuration au container de son application
	 *
	 * @return array
	 */
	public function addConfig(): array
	{
		return [
			'twig.path' => add([
				'contact' => __DIR__ . '/views'
			]),
		];
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return 'Contact';
	}
}
