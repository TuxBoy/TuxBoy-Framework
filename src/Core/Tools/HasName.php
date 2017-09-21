<?php

namespace TuxBoy\Tools;

trait HasName
{
    /**
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param $name string
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
