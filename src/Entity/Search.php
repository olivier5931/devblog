<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Search
{
	/**
	 * @var string|null
	 */
	private $title;

	/**
	 * @var string|null
	 */
	private $category;

	/**
	 * @return string|null
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string|null $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string|null
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * @param string|null $category
	 */
	public function setCategory($category)
	{
		$this->category = $category;
	}
}
