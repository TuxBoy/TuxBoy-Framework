<?php

namespace TuxBoy\Form;

class Select extends Element
{
    /**
     * @var array
     */
    private $values = [];

    /**
     * @var string|null
     */
    private $selected;

    public function __construct(
        ?string $name = null,
        array $values = null,
        ?string $selected = null,
        ?string $id = null
    ) {
        parent::__construct('select', true);
        if (null !== $name) {
            $this->setAttribute('name', $name);
        }
        if (null !== $selected) {
            $this->selected = $selected;
        }
        if (null !== $values) {
            $this->values = $values;
        }
    }

    public function getContent()
    {
        $content = parent::getContent();
        if (!isset($content)) {
            $content = '';
            foreach ($this->values as $value => $caption) {
                $option = new Option($value, $caption);
                if ($caption === $this->selected) {
                    $option->setAttribute('selected');
                }
                $content .= (string) $option;
            }

            $this->setContent($content);
        }

        return $content;
    }
}
