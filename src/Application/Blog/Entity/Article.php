<?php

namespace TuxBoy\Application\Blog\Entity;

class Article
{

	/**
	 * @var string
	 */
    public $name;

	/**
	 * @var string
	 */
    public $slug;

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
		$this->slug = $slug;
	}
}
