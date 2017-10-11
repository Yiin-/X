<?php

namespace App\Domain\Events\Authentication;

class UserWasCreated
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}