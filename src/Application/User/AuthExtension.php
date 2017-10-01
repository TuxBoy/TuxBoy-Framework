<?php
namespace App\User;

class AuthExtension extends \Twig_Extension
{

    /**
     * @var AuthService
     */
    private $authService;

    /**
     * AuthExtension constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('current_user', [$this->authService, 'getUser'])
        ];
    }
}