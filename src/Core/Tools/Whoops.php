<?php

namespace Core\Tools;

use Core\Handler\HandlerInterface;
use Core\Plugin\Registrable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class WhoopsPlugin.
 */
class Whoops implements Registrable, HandlerInterface
{
    public function handle()
    {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
    }
}
