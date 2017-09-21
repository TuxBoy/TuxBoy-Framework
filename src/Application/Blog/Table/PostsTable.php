<?php

namespace App\Blog\Table;

use App\Blog\Entity\Post;
use Cake\ORM\Table;

class PostsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Categories');
        $this->setEntityClass(Post::class);
    }
}
