<?php
namespace App\Controller;

use App\Concern\Has_Civility;
use App\Model\Article;
use Core\Builder\Builder;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

class HomeController
{

	public function index(ServerRequest $request)
	{
		/** @var $article Article|Has_Civility */
		$article = Builder::create(Article::class);
        dump($request);
		return new Response(200, [], 'Coucou');
	}

}
