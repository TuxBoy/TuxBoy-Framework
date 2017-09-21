<?php

namespace TuxBoy\Session;

class FlashService
{
    const FLASH = 'flash';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array
     */
    private $messages;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $message
     */
    public function success(string $message): void
    {
        $flash = $this->session->get(self::FLASH, []);
        $flash['success'] = $message;
        $this->session->set(self::FLASH, $flash);
    }

    /**
     * @param string $type
     *
     * @return null|string
     */
    public function get(string $type): ?string
    {
        if (null === $this->messages) {
            $this->messages = $this->session->get(self::FLASH, []);
            $this->session->delete(self::FLASH);
        }

        return array_key_exists($type, $this->messages) ? $this->messages[$type] : null;
    }
}
