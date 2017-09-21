<?php

namespace TuxBoy\Router;

class Route
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Route constructor.
     *
     * @param string   $name
     * @param callable $callable
     * @param array    $parameters
     */
    public function __construct(string $name, callable $callable, array $parameters)
    {
        $this->name = $name;
        $this->callable = $callable;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable
     */
    public function getCallback(): callable
    {
        return $this->callable;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->parameters;
    }
}
