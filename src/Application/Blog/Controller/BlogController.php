<?php

namespace TuxBoy\Application\Blog\Controller;

use Core\Builder\Builder;
use Core\Controller\Controller;
use GuzzleHttp\Psr7\ServerRequest;
use TuxBoy\Application\Blog\Entity\Article;
use TuxBoy\Application\Blog\Entity\Category;
use TuxBoy\Application\Blog\Repository\ArticleRepository;
use TuxBoy\Application\Blog\Repository\CategoryRepository;

/**
 * BlogController.
 */
class BlogController extends Controller
{
    public $entities = [Article::class, Category::class];

    public function index(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        return $this->twig->render('blog/index.twig', compact('articles'));
    }

    /**
     * @param ServerRequest      $request
     * @param ArticleRepository  $articleRepository
     *
     * @param CategoryRepository $categoryRepository
     * @return string
     */
    public function create(ServerRequest $request, ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
    {
        if ($request->getMethod() === 'POST') {
            $data = $this->getParams($request, ['name', 'slug', 'content', 'category_id']);

            $article = Builder::create(Article::class, [$data]);
            $articleRepository->insert($article);
            $this->flash->success("L'aticle a bien été créé");

            return $this->redirectTo('/blog');
        }
        $categories = $categoryRepository->findAll();
        return $this->twig->render('blog/create.twig', compact('categories'));
    }

    public function show(string $slug, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->findOneBy(['slug' => $slug]);

        return $this->twig->render('blog/show.twig', compact('article'));
    }
}
