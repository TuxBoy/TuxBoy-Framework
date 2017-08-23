<?php

namespace TuxBoy\Application\Blog\Controller;

use TuxBoy\Application\Blog\Repository\ArticleRepository;

/**
 * BlogController.
 * @set("articles")
 */
class BlogController
{

	public function index(\Twig_Environment $twig, ArticleRepository $articleRepository)
	{
		$articles = $articleRepository->findAll();
		return $twig->render('blog/index.twig', compact('articles'));
	}

}
