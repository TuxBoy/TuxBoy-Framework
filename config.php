<?php

use Core\Tools\Whoops;
use function DI\env;
use Core\Priority;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;
use Psr\Container\ContainerInterface;

return [

	Priority::APP => [],

	Priority::CORE => [
		\App\Blog\Entity\Post::class => [
			\Core\Tools\HasTime::class
		]
	],

    Priority::PLUGIN => []

];
