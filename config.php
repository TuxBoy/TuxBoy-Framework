<?php

use function DI\string;
use TuxBoy\Priority;

return [

	Priority::APP => [
        'basepath'  => __DIR__,
        'twig.path' => \DI\add([string('{basepath}/res/views')]),
    ],

	Priority::CORE => [
		\App\Blog\Entity\Post::class => [
			\TuxBoy\Tools\HasTime::class
		]
	],

    Priority::PLUGIN => []

];
