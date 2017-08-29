<?php

namespace Core\Tools;

use Core\Handler\HandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class WhoopsPlugin.
 */
class Whoops implements HandlerInterface
{
    public function handle(): void
    {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
    }
}
