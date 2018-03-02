<?php
namespace App\Link\Entity;

use TuxBoy\Annotation\Length;
use TuxBoy\Annotation\Option;
use TuxBoy\Annotation\Set;
use TuxBoy\Entity;
use TuxBoy\Tools\HasName;

/**
 * Class Link
 *
 * @Set(tableName="links")
 */
class Link extends Entity
{

    use HasName;

    /**
     * @var string
     *
     * @Length(60)
     * @Option(placeholder="url")
     */
    public $slug;

    /**
     * @var string
     *
     * @Option(type="email", mandatory=true, placeholder="email")
     */
    public $email;

    /**
     * @var Category
     */
    public $category;

}
