<?php
namespace Core\Tools;

trait Has_Time
{

	/**
	 * @var \DateTime
	 */
	public $createdAt;

	/**
	 * @var \DateTime
	 */
	public $updatedAt;

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt(): \DateTime
	{
		return $this->createdAt;
	}

	/**
	 * @param $created_at \DateTime
	 */
	public function setCreatedAt($created_at)
	{
		$this->createdAt = $created_at;
	}

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
