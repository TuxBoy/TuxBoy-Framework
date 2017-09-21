<?php

namespace App\Home\Controller;

use TuxBoy\Controller\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view->render('@home/index.twig');
    }
}
