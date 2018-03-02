<?php

use App\Link\Entity\Link;
use App\Link\Entity\Tag;
use App\Link\Table\LinksTable;
use App\Link\Table\TagsTable;
use Cake\ORM\TableRegistry;
use function DI\add;
use function DI\factory;
use TuxBoy\Html\BootstrapMenu;
use TuxBoy\Html\Menu;

return [
    'twig.path' => add([
        'link' => __DIR__ . '/views/'
    ]),
    'entities' => add([
        Link::class,
        Tag::class
    ]),
    'menu.admin' => add([
        new BootstrapMenu('Links', 'link.index')
    ]),
    'menu' => add([
        new BootstrapMenu('Les liens', 'link.index')
    ]),
    LinksTable::class => factory(function () {
        return TableRegistry::get('Links', ['className' => LinksTable::class]);
    }),
    TagsTable::class => factory(function () {
        return TableRegistry::get('Links', ['className' => TagsTable::class]);
    })
];