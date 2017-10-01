<?php
namespace App\User;

use App\User\Table\UsersTable;
use TuxBoy\Exception\NoRecordException;
use TuxBoy\Session\SessionInterface;
use TuxBoy\Tools\Password;
use TuxBoy\User\AuthServiceInterface;
use TuxBoy\User\User;

class AuthService implements AuthServiceInterface
{

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * AuthService constructor.
     * @param UsersTable $usersTable
     * @param SessionInterface $session
     */
    public function __construct(UsersTable $usersTable, SessionInterface $session)
    {
        $this->usersTable = $usersTable;
        $this->session = $session;
    }

    /**
     * @param string $username
     * @param string $password
     * @return null|User
     */
    public function login(string $username, string $password): ?User
    {
        if (empty($username) || empty($password)) {
            return null;
        }
        /** @var $user User */
        $user = $this->usersTable->find()->where(['username' => $username])->first();
        if ($user && Password::verify($password, $user->get('password'))) {
            $this->session->set('auth.user', $user->get('id'));
            return $user;
        }
        return null;
    }

    /**
     * @return null|User
     */
    public function getUser(): ?User
    {
        $userId = $this->session->get('auth.user');
        if ($userId) {
            try {
                /** @var $user User */
                $user = $this->usersTable->get($userId);
                return $user;
            } catch (NoRecordException $exception) {
                $this->session->delete('auth.user');
                return null;
            }

        }
        return null;
    }

    public function logout()
    {
        $this->session->delete('auth.user');
    }
}