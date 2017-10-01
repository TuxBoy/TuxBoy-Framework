<?php
namespace App\Admin\Controller;

use TuxBoy\Controller\Controller;

class AdminController extends Controller
{

    public function index()
    {
        return $this->view->render('@admin/index.twig');
    }

}