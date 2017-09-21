<?php

namespace TuxBoy\Form;

class Input extends Element
{
    public function __construct(?string $name = null, ?string $value = null, ?string $id = null)
    {
        parent::__construct('input');
        if (null !== $name) {
            $this->setAttribute('name', $name);
        }
        if (null !== $value) {
            $this->setAttribute('value', $value);
        }
        if (null !== $id) {
            $this->setAttribute('id', $id);
        }
    }
}
