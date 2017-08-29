<?php

namespace TuxBoy\Application\Blog\Repository;

use Core\Database\Repository;
use TuxBoy\Application\Blog\Entity\Article;

class ArticleRepository extends Repository
{
    protected static $ENTITY = Article::class;
}
