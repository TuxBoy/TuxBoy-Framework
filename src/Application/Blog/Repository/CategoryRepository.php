<?php
namespace App\Blog\Repository;

use Core\Database\Repository;
use App\Blog\Entity\Category;

class CategoryRepository extends Repository
{

    protected static $ENTITY = Category::class;

}