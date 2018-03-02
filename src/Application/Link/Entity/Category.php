<?php
namespace App\Link\Entity;

use TuxBoy\Entity;
use TuxBoy\Tools\HasName;
use TuxBoy\Annotation\Set;

/**
 * Category Links
 *
 * @Set(tableName="link_categories")
 */
class Category extends Entity
{

    use HasName;


}