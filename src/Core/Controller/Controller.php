<?php
namespace Core\Controller;

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
	 * Controller constructor.
	 *
	 * @param ContainerInterface $container
	 * @param Twig_Environment  $twig
	 */
	public function __construct(ContainerInterface $container, Twig_Environment $twig)
	{
		$this->container = $container;
		$this->twig = $twig;
	}

	/**
	 * @param ServerRequestInterface $request
	 * @param array                  $dataAllow
	 * @return array
	 */
	public function getParams(ServerRequestInterface $request, array $dataAllow = []): array
	{
		return array_filter($request->getParsedBody(), function ($item) use ($dataAllow) {
			return in_array($item, $dataAllow);
		}, ARRAY_FILTER_USE_KEY);
	}
}
