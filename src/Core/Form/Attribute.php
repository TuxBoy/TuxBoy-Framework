<?php

namespace TuxBoy\Form;

class Attribute
{
    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null
     */
    private $value;

    public function __construct(?string $name = null, ?string $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->name . (null !== $this->value ? '="' . $this->value . '"' : '');
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }
}
