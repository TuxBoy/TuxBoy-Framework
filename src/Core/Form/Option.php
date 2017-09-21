<?php

namespace TuxBoy\Form;

class Option extends Element
{
    /**
     * Option constructor.
     *
     * @param null|string $value
     * @param null|string $caption
     */
    public function __construct(?string $value = null, ?string $caption = null)
    {
        parent::__construct('option', true);
        if (null !== $value) {
            $this->setAttribute('value', $value);
        }
        if (null !== $caption) {
            $this->setContent($caption);
        }
    }
}
