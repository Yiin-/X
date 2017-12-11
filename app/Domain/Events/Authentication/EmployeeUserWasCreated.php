<?php

namespace App\Domain\Events\Authentication;

use App\Domain\Model\Authentication\User\User;

class EmployeeUserWasCreated
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}