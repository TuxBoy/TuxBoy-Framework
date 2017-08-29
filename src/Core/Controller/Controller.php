<?php

namespace Core\Controller;

use Core\Session\FlashService;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig_Environment;

class Controller
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var FlashService
     */
    protected $flash;

    /**
     * Controller constructor.
     *
     * @param ContainerInterface $container
     * @param Twig_Environment   $twig
     */
    public function __construct(ContainerInterface $container, Twig_Environment $twig, FlashService $flash)
    {
        $this->container = $container;
        $this->twig = $twig;
        $this->flash = $flash;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $dataAllow
     *
     * @return array
     */
    public function getParams(ServerRequestInterface $request, array $dataAllow = []): array
    {
        return array_filter($request->getParsedBody(), function ($item) use ($dataAllow) {
            return in_array($item, $dataAllow, true);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param ServerRequestInterface $request
     * @param string                 $field
     *
     * @return mixed
     */
    public function getParam(ServerRequestInterface $request, string $field)
    {
        return array_key_exists($field, $request->getParsedBody())
            ? $request->getParsedBody()[$field]
            : null;
    }

    /**
     * @param string $route
     *
     * @return \GuzzleHttp\Psr7\MessageTrait
     */
    public function redirectTo(string $route)
    {
        $response = new Response();

        return $response->withStatus(200)->withHeader('Location', $route);
    }
}
