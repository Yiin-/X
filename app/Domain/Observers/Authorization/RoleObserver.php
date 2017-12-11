<?php

namespace App\Domain\Observers\Authorization;

use Mail;
use App\Domain\Mail\WelcomeMessageForUser;
use App\Domain\Model\Authorization\Role\Role;

class RoleObserver
{
    public function deleted(Role $role)
    {
        $role->roleable->touch();
    }
}