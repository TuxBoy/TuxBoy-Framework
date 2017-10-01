<?php
namespace App\User\Entity;

use TuxBoy\Annotation\Set;
use TuxBoy;

/**
 * User
 *
 * @Set(tableName="users")
 */
class User extends TuxBoy\User\User
{

    /**
     * @var string
     */
    public $email;

}