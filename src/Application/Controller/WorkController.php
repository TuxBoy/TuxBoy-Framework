<?php
namespace TuxBoy\Application\Controller;

use Twig_Environment;

class WorkController
{

    public function index(Twig_Environment $twig)
    {
        return $twig->render('/works/index.twig');
    }

}