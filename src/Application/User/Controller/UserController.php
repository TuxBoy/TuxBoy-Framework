<?php
namespace App\User\Controller;

use App\User\AuthService;
use App\User\Entity\User;
use App\User\Form\UserForm;
use GuzzleHttp\Psr7\ServerRequest;
use TuxBoy\Builder\Builder;
use TuxBoy\Controller\Controller;
use TuxBoy\Controller\Http;
use TuxBoy\Router\Router;

class UserController extends Controller
{

    /**
     * @param ServerRequest $request
     * @param UserForm $form
     * @param Router $router
     * @param AuthService $auth
     * @return string
     */
    public function login(ServerRequest $request, UserForm $form, Router $router, AuthService $auth)
    {
        if ($request->getMethod() === Http::POST) {
            $username = $request->getParsedBody()['username'];
            $password = $request->getParsedBody()['password'];
            if ($auth->login($username, $password)) {
                $this->flash->success('Vous êtes maintenant connecté.');
                return $this->redirectTo($router->generateUri('root'));
            } else {
                $this->flash->error('Votre login et mot de passe ne correspondent pas.');
            }
        }
        return $this->view->render('@user/login.twig', [
            'user'     => Builder::create(User::class),
            'userForm' => $form->formLogin()
        ]);
    }

    /**
     * @param AuthService $authService
     * @param Router $router
     * @return \GuzzleHttp\Psr7\MessageTrait
     */
    public function logout(AuthService $authService, Router $router)
    {
        $authService->logout();

        $this->flash->success('Vous êtes maintenant déconnecté.');
        return $this->redirectTo($router->generateUri('root'));
    }
}