<?php

namespace App\Blog\Entity;

use TuxBoy\Annotation\Set;
use TuxBoy\Entity;
use TuxBoy\Tools\HasName;

/**
 * Category.
 * @Set(tableName="categories")
 */
class Category extends Entity
{
    use HasName;

    /**
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $slug;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param $id int
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}
