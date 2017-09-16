<?php
namespace App\Blog;

use App\Blog\Controller\BlogController;
use App\Blog\Controller\CategoryController;
use App\Blog\Entity\Article;
use App\Blog\Entity\Category;
use Core\ApplicationInterface;
use Core\Router\Router;
use function DI\add;


/**
 * Class Application
 */
class Application implements ApplicationInterface
{

    /**
     * @return array
     */
    public function entites(): array
    {
        return [Article::class, Category::class];
    }

    /**
     * @param Router $router
     */
    public function getRoutes(Router $router): void
    {
        $router->get('/blog', [BlogController::class, 'index'], 'blog.index');
        $router->get('/blog/new', [BlogController::class, 'create'], 'blog.new');
        $router->get('/blog/list', [BlogController::class, 'listToArticles'], 'blog.list.articles');
        $router->get('/blog/{slug}', [BlogController::class, 'show'], 'blog.show');
        $router->post('/blog/new', [BlogController::class, 'create']);
        $router->get('/blog/editer/{id}', [BlogController::class, 'udpate'], 'blog.update');
        $router->get('/blog/categorie/new', [CategoryController::class, 'create'], 'blog.category.new');
        $router->post('/blog/categorie/new', [CategoryController::class, 'create']);
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
                'blog' => __DIR__ . '/views/'
            ]),
            'entities' => add([
                Article::class,
                Category::class
            ]),
        ];
    }

    public function getName(): string
    {
        return str_replace('\\Application', '', get_class($this));
    }
}