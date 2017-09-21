<?php

namespace TuxBoy\Tools;

trait HasUpdatedAt
{
    /**
     * @var \DateTime
     */
    public $updated_at;

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param $updated_at \DateTime
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}
