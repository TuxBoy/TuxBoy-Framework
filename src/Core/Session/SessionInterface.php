<?php

namespace TuxBoy\Session;

interface SessionInterface
{
    /**
     * Récupère une inforamtion en session.
     *
     * @param string $key
     * @param $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Ajoute une information en session.
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void;

    /**
     * Supprime une clef en session.
     *
     * @param string $key
     */
    public function delete(string $key): void;
}
