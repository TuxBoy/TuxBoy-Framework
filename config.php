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
		\TuxBoy\Application\Blog\Entity\Article::class => [
			\TuxBoy\Application\Blog\Civility::class,
			\Core\Tools\HasTime::class
		]
	],

    Priority::PLUGIN => []

];
