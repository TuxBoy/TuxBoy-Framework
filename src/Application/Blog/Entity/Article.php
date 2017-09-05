<?php

namespace TuxBoy\Application\Blog\Entity;

use Cocur\Slugify\Slugify;
use Core\Entity;

/**
 * Class Article.
 *
 * @set posts
 */
class Article extends Entity
{
    /**
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $slug;

    /**
     * @var text
     */
    public $content;

    /**
     * @link belongsTo
     *
     * @var \TuxBoy\Application\Blog\Entity\Category
     */
    public $category;

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param $name mixed
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param $slug string
     */
    public function setSlug($slug)
    {
        if (isset($this->name) && !isset($this->slug)) {
            $this->slug = (new Slugify())->slugify($this->name);
        } else {
            $this->slug = $slug;
        }
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param $content text
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param $category Category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
}
