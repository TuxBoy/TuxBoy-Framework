<?php

namespace App\Blog\Controller;

use App\Blog\Entity\Category;
use Psr\Http\Message\ServerRequestInterface as Request;
use TuxBoy\Builder\Builder;
use TuxBoy\Controller\Controller;
use Zend\Stdlib\Response;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $data = $this->getParams($request, ['name', 'slug']);
            $category = Builder::create(Category::class, [$data]);
                        // @TODO à remplacer par cakeorm
            return $this->redirectTo('/');
        }
        $response = new Response();
        return $this->twig->render('blog/category/create.twig');
    }
}
