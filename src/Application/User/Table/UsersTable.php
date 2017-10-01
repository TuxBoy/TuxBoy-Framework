<?php
namespace App\User\Table;

use App\User\Entity\User;
use Cake\ORM\Table;

class UsersTable extends Table
{

    public function initialize(array $config)
    {
        $this->setEntityClass(User::class);
    }

}