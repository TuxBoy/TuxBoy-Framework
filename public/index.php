<?php

use GuzzleHttp\Psr7\ServerRequest;

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
    $dotenv->load();
}
$config = require dirname(__DIR__) . '/config.php';
$applications = [
		\App\Admin\Application::class,
    \App\Home\Application::class,
		\App\Contact\Application::class,
		\App\Link\Application::class,
];
$app = new TuxBoy\App($config, $applications);

$response = $app->run(ServerRequest::fromGlobals());
\Http\Response\send($response);
