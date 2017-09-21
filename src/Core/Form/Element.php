<?php

namespace TuxBoy\Form;

class Element
{
    /**
     * @var null|string
     */
    private $name;

    /**
     * @var bool
     */
    private $endTag;

    /**
     * @var Attribute[]
     */
    private $attributes = [];

    /**
     * @var string|string[]
     */
    private $content;

    /**
     * Element constructor.
     *
     * @param null|string $name   Le nom de l'element
     * @param bool        $endTag Si true, sera alors une balise html fermante
     */
    public function __construct(?string $name = null, bool $endTag = false)
    {
        $this->name = $name;
        $this->endTag = $endTag;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $content = $this->getContent();

        return '<' . $this->name . ' ' . implode(' ', $this->attributes) . '>'
            . (($this->endTag || isset($content)) ? $content . '</' . $this->name . '>' : '');
    }

    /**
     * @param string|null $name
     * @param string|null $value
     *
     * @return Element
     */
    public function setAttribute(?string $name = null, ?string $value = null): self
    {
        $attribute = new Attribute($name, $value);
        $this->attributes[$attribute->getName()] = $attribute;

        return $this;
    }

    /**
     * @return string|string[]
     */
    public function getContent()
    {
        if (is_array($this->content)) {
            if ($this->content) {
            } else {
                $content = '';
            }

            return $content;
        }

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
