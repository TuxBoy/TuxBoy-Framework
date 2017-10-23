<?php

use TuxBoy\Priority;

return [

	Priority::APP => [],

	Priority::CORE => [
		\App\Blog\Entity\Post::class => [
			\TuxBoy\Tools\HasTime::class
		]
	],

    Priority::PLUGIN => []

];
