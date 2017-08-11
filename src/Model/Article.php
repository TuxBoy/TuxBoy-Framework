<?php
namespace App\Model;

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
	 * @var string
	 */
	public $content;

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
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
	 * @param $name string
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getContent(): string
	{
		return $this->content;
	}

	/**
	 * @param $content string
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

}
