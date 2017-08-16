<?php

use GuzzleHttp\Psr7\ServerRequest;

require __DIR__ . '/../vendor/autoload.php';

$config_file = require dirname(__DIR__) . '/config.php';
$app         = new Core\App(ServerRequest::fromGlobals(), $config_file);

require __DIR__ . '/../res/routes.php';

$response = $app->run();
\Http\Response\send($response);
