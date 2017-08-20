<?php
namespace TuxBoy\Application\Controller;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ServerRequestInterface;
use Twig_Environment;

class HomeController
{

	public function index(ServerRequestInterface $request, Twig_Environment $twig, Connection $db)
	{

	    return $twig->render('home/index.twig');
	}

}
