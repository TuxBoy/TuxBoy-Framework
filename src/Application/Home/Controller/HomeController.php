<?php

namespace TuxBoy\Application\Home\Controller;

use Core\Controller\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->twig->render('home/index.twig');
    }
}
