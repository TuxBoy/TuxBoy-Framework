<?php

namespace TuxBoy\Application\Blog\Controller;

use Cocur\Slugify\Slugify;
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

    /**
     * @param ServerRequest $request
     * @param ArticleRepository $articleRepository
     * @param Slugify $slugify
     * @return string
     */
	public function create(ServerRequest $request, ArticleRepository $articleRepository, Slugify $slugify)
	{
		if ($request->getMethod() === 'POST') {
            $slug = $this->getParam($request, 'slug');
		    $data = $this->getParams($request, ['name', 'slug', 'content']);
            $name = $this->getParam($request, 'name');

            $data['slug'] = empty($slug) ? $slugify->slugify($name) : $slug;
            $articleRepository->insert($data);
            $this->flash->success("L'aticle a bien été créé");
            return $this->redirectTo('/blog');
        }
		return $this->twig->render('blog/create.twig');
	}

	public function show(string $slug, ArticleRepository $articleRepository)
	{
		$article = $articleRepository->findOneBy(['slug' => $slug]);
		return $this->twig->render('blog/show.twig', compact('article'));
	}

}
