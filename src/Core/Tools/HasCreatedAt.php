<?php

namespace TuxBoy\Tools;

trait HasCreatedAt
{
    /**
     * @var \DateTime
     */
    public $created_at;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @param $created_at \DateTime
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
}
