<?php
namespace App\Controller;

use App\Concern\Has_Civility;
use App\Model\Article;
use Core\Builder\Builder;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

class HomeController
{

	public function index(ServerRequestInterface $request)
	{
		/** @var $article Article|Has_Civility */
		$article = Builder::create(Article::class);
        $response = new Response();
		$response->getBody()->write('Coucou');
        return $response;
	}

}
