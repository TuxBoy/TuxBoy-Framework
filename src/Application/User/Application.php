<?php
namespace App\User;

use App\User\Controller\UserController;
use App\User\Entity\User;
use App\User\Table\UsersTable;
use Cake\ORM\TableRegistry;
use function DI\factory;
use function DI\get;
use TuxBoy\ApplicationInterface;
use TuxBoy\Router\Router;
use function DI\add;

/**
 * The User application
 */
class Application implements ApplicationInterface
{
    /**
     * Définie les routes de l'application.
     *
     * @param Router $router
     */
    public function getRoutes(Router $router): void
    {
        $router->get('/login', [UserController::class, 'login'], 'user.login');
        $router->post('/login', [UserController::class, 'login']);
        $router->get('/logout', [UserController::class, 'logout'], 'user.logout');
    }

    /**
     * Pour ajouter la configuration au container de son application.
     *
     * @return array
     */
    public function addConfig(): array
    {
        return [
            'twig.path' => add([
               'user'  => __DIR__ . '/views/'
            ]),
            'twig.extensions' => add([
                get(AuthExtension::class)
            ]),
            'entities' => add([
                User::class
            ]),
            'goaop.aspect' => add([
                get(AuthAspect::class)
            ]),
            UsersTable::class => factory(function () {
                return TableRegistry::get('Users', ['className' => UsersTable::class]);
            })
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'User';
    }
}
