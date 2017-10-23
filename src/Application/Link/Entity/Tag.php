<?php
namespace App\Link\Entity;

use TuxBoy\Annotation\Set;
use TuxBoy\Entity;
use TuxBoy\Tools\HasName;

/**
 * Class Category
 *
 * @Set(tableName="tags")
 */
class Tag extends Entity
{

    use HasName;
}
