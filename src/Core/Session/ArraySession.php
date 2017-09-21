<?php

namespace TuxBoy\Session;

class ArraySession implements SessionInterface
{
    /**
     * @var array
     */
    private $session = [];

    /**
     * Récupère une inforamtion en session.
     *
     * @param string $key
     * @param $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->session)) {
            return $this->session[$key];
        }

        return $default;
    }

    /**
     * Ajoute une information en session.
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->session[$key] = $value;
    }

    /**
     * Supprime une clef en session.
     *
     * @param string $key
     */
    public function delete(string $key): void
    {
        unset($this->session[$key]);
    }
}
