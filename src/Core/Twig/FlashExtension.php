<?php

namespace TuxBoy\Twig;

use TuxBoy\Session\FlashService;

class FlashExtension extends \Twig_Extension
{
    /**
     * @var FlashService
     */
    private $flash;

    public function __construct(FlashService $flash)
    {
        $this->flash = $flash;
    }

    /**
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('flash', [$this, 'getFlash'])
        ];
    }

    /**
     * @param string $type
     *
     * @return null|string
     */
    public function getFlash(string $type): ?string
    {
        return $this->flash->get($type);
    }
}
