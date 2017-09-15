<?php

namespace App\Blog\Repository;

use Core\Database\Repository;
use App\Blog\Entity\Article;

class ArticleRepository extends Repository
{
    protected static $ENTITY = Article::class;
}
