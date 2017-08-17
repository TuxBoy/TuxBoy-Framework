<?php

use Core\Priority;

return [

	Priority::APP => [
	    Twig_Environment::class => function () {
            $loader = new Twig_Loader_Filesystem(__DIR__ . '/res/views');
            return new Twig_Environment($loader);
        }
    ],

	Priority::CORE => []

];
