<?php
namespace TuxBoy\Application\Controller;

use Twig_Environment;

class UserController
{

    public function login(Twig_Environment $twig)
    {
        return $twig->render('/user/login.twig');
    }

}