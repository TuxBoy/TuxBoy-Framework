<?php

namespace TuxBoy\Application\Home\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Twig_Environment;

class HomeController
{
    public function index(ServerRequestInterface $request, Twig_Environment $twig)
    {
        return $twig->render('home/index.twig');
    }
}
