<?php

namespace TuxBoy\Application\Blog\Controller;

use Core\Controller\Controller;
use GuzzleHttp\Psr7\ServerRequest;
use TuxBoy\Application\Blog\Entity\Article;
use TuxBoy\Application\Blog\Entity\Category;
use TuxBoy\Application\Blog\Repository\ArticleRepository;

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

	public function create(ServerRequest $request, ArticleRepository $articleRepository)
	{
		if ($request->getMethod() === 'POST') {
			$data = $this->getParams($request, ['name', 'slug', 'content']);
			$articleRepository->insert($data);
		}
		return $this->twig->render('blog/create.twig');
	}

}
