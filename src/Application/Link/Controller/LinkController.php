<?php
namespace App\Link\Controller;

use App\Link\Entity\Link;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use TuxBoy\Builder\Builder;
use TuxBoy\Controller\Controller;

class LinkController extends Controller
{

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        $link = Builder::create(Link::class);
        return $this->view->render('@link/index.twig', compact('link'));
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function create()
    {
        $link = Builder::create(Link::class);
        return $this->view->render('@link/create.twig', compact('link'));
    }

    public function store(ServerRequestInterface $request)
    {
        dump($request->getAttributes()); die();
    }
}
