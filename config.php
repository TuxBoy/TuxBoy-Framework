<?php

use TuxBoy\Tools\Whoops;
use function DI\env;
use TuxBoy\Priority;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteParser\Std;
use Psr\Container\ContainerInterface;

return [

	Priority::APP => [],

	Priority::CORE => [
		\App\Blog\Entity\Post::class => [
			\TuxBoy\Tools\HasTime::class
		]
	],

    Priority::PLUGIN => []

];
