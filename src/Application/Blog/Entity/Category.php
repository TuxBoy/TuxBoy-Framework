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
}
