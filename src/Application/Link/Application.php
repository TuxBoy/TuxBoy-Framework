<?php
namespace App\Link;

use App\Link\Controller\LinkController;
use App\Link\Entity\Link;
use App\Link\Entity\Tag;
use App\Link\Table\LinksTable;
use App\Link\Table\TagsTable;
use Cake\ORM\TableRegistry;
use function DI\add;
use function DI\factory;
use TuxBoy\ApplicationInterface;
use TuxBoy\Router\Router;

class Application implements ApplicationInterface
{

    /**
     * Définie les routes de l'application.
     *
     * @param Router $router
     */
    public function getRoutes(Router $router): void
    {
        $router->get('/links', [LinkController::class, 'index'], 'link.index');
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
                'link' => __DIR__ . '/views/'
            ]),
            'entities' => add([
                Link::class,
                Tag::class
            ]),
            'menu.admin' => add([
                Link::class
            ]),
            LinksTable::class => factory(function () {
                return TableRegistry::get('Links', ['className' => LinksTable::class]);
            }),
            TagsTable::class => factory(function () {
                return TableRegistry::get('Links', ['className' => TagsTable::class]);
            })
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Link';
    }
}
