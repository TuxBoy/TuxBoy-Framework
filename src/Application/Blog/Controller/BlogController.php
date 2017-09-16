<?php

namespace App\Blog\Controller;

use Core\Builder\Builder;
use Core\Controller\Controller;
use GuzzleHttp\Psr7\ServerRequest;
use App\Blog\Entity\Article;
use App\Blog\Repository\ArticleRepository;
use App\Blog\Repository\CategoryRepository;

/**
 * BlogController.
 */
class BlogController extends Controller
{

    /**
     * @param ArticleRepository $articleRepository
     * @return string
     */
    public function index(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        return $this->twig->render('@blog/index.twig', compact('articles'));
    }

    public function listToArticles(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        return $this->twig->render('@blog/list.twig', compact('articles'));
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
        return $this->twig->render('@blog/create.twig', compact('categories'));
    }

    public function show(string $slug, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->findOneBy(['slug' => $slug]);

        return $this->twig->render('@blog/show.twig', compact('article'));
    }
}
