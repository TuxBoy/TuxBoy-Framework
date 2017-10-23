<?php
namespace App\Link\Controller;

use App\Link\Entity\Link;
use TuxBoy\Builder\Builder;
use TuxBoy\Controller\Controller;

class LinkController extends Controller
{

    public function index()
    {
        $link = Builder::create(Link::class);
        return $this->view->render('@link/index.twig', compact('link'));
    }
}
