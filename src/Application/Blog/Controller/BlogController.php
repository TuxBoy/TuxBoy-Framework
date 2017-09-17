<?php

namespace App\Blog\Controller;

use App\Blog\Table\CategoriesTable;
use App\Blog\Table\PostsTable;
use Core\Builder\Builder;
use Core\Controller\Controller;
use DI\NotFoundException;
use GuzzleHttp\Psr7\ServerRequest;
use App\Blog\Entity\Post;

/**
 * BlogController.
 */
class BlogController extends Controller
{

    /**
     * @param PostsTable $postsTable
     * @return string
     */
    public function index(PostsTable $postsTable)
    {
        $articles = $postsTable->find()->contain(['Categories'])->all();
        return $this->view->render('@blog/index.twig', compact('articles'));
    }

    public function listToArticles(PostsTable $postsTable)
    {
        $articles = $postsTable->find('all');
        return $this->view->render('@blog/list.twig', compact('articles'));
    }

    /**
     * @param ServerRequest $request
     * @param PostsTable $postsTable
     * @return string
     */
    public function create(ServerRequest $request, PostsTable $postsTable, CategoriesTable $categories)
    {
        if ($request->getMethod() === 'POST') {
            $data = $this->getParams($request, ['name', 'slug', 'content', 'category_id']);

            $article = Builder::create(Post::class, [$data]);
            $postsTable->save($article);
            $this->flash->success("L'aticle a bien été créé");

            return $this->redirectTo('/blog');
        }
        $categories = $categories->find()->all();
        return $this->view->render('@blog/create.twig', compact('categories'));
    }

    /**
     * @param int $id
     * @param ServerRequest $request
     * @param PostsTable $postsTable
     * @return string
     */
    public function update(int $id, ServerRequest $request, PostsTable $postsTable)
    {
        /** @var $article Post */
        $article = $postsTable->get($id);
        if ($request->getMethod() === 'POST') {
            $data = $this->getParams($request, ['name', 'slug', 'content', 'category_id']);
            $postsTable->patchEntity($article, $data);
            $postsTable->save($article);
        }
        return $this->view->render('@blog/update.twig', compact('article'));
    }

    public function delete(int $id, PostsTable $postsTable)
    {
        $article = $postsTable->get($id);
        if (!$article) {
            throw new NotFoundException();
        }
        $postsTable->delete($article);
        return $this->redirectTo('blog.list');
    }

    public function show(string $slug, PostsTable $postsTable)
    {
        $article = $postsTable->find()->where(['slug' => $slug])->first();

        return $this->view->render('@blog/show.twig', compact('article'));
    }
}
