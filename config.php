<?php

use Core\Priority;

return [

	Priority::APP => [

	],

	Priority::CORE => [
		\App\Model\Article::class => [
			\App\Concern\Has_Online::class
		]
	]

];
