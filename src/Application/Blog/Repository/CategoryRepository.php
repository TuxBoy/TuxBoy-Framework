<?php
namespace TuxBoy\Application\Blog\Repository;

use Core\Database\Repository;
use TuxBoy\Application\Blog\Entity\Category;

class CategoryRepository extends Repository
{

    protected static $ENTITY = Category::class;

}