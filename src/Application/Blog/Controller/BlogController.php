<?php

namespace TuxBoy\Application\Blog\Controller;

use TuxBoy\Application\Blog\Entity\Article;
use TuxBoy\Application\Blog\Entity\Category;
use TuxBoy\Application\Blog\Repository\ArticleRepository;

/**
 * BlogController.
 */
class BlogController
{

	public $entities = [Article::class, Category::class];

	public function index(\Twig_Environment $twig, ArticleRepository $articleRepository)
	{
		$articles = $articleRepository->findAll();
		return $twig->render('blog/index.twig', compact('articles'));
	}

}
