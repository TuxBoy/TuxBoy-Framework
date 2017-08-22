<?php

use GuzzleHttp\Psr7\ServerRequest;

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
    $dotenv->load();
}

$config_file = require dirname(__DIR__) . '/config.php';
$app = new Core\App($config_file);
\Core\ApplicationApsect::getInstance()->init([
	'debug'        => $app->getContainer()->get('dev'),
	'appDir'       => dirname(__DIR__) . '/src/',
	'cacheDir'     => false, // dirname(__DIR__) . '/cache/',
	'includePaths' => []
]);

require __DIR__ . '/../res/routes.php';

$response = $app->run(ServerRequest::fromGlobals());
\Http\Response\send($response);
