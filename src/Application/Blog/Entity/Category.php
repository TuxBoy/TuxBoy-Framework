<?php

namespace TuxBoy\Application\Blog\Entity;

use Core\Entity;
use Core\Tools\HasName;

/**
 * Category.
 *
 * @set categories
 */
class Category extends Entity
{
    use HasName;

    /**
     * @var integer
     */
    public $id;

    /**
     * @length 60
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
