<?php

require __DIR__ . '/../vendor/autoload.php';


$config_file = require dirname(__DIR__) . '/config.php';


\Core\Plugin::current()->addBuilder(\Core\Priority::CORE, $config_file[\Core\Priority::CORE]);

$app = new Core\App($config_file);
$app->get('/', [\App\Controller\HomeController::class, 'index']);
$app->run();
