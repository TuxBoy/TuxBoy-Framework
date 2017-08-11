<?php
namespace App\Controller;

use App\Concern\Has_Civility;
use App\Model\Article;
use Core\Builder\Builder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class HomeController
{

	public function index(Response $response, Request $request)
	{
		/** @var $article Article|Has_Civility */
		$article = Builder::create(Article::class);
		dump($article);
		echo 'Salut';
	}

}
