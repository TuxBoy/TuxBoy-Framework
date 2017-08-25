<?php

namespace TuxBoy\Application\Blog\Entity;

/**
 * Class Article
 *
 * @set posts
 */
class Article
{

	/**
	 * @var string
	 */
    public $name;

	/**
	 * @length 60
	 * @var string
	 */
    public $slug;

	/**
	 * @var text
	 */
    public $content;


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
}
