<?php

namespace App\Domain\Observers\Authentication;

use App\Domain\Model\Authentication\User\User;

class UserObserver
{
    public function creating(User $user)
    {
        if (auth()->check() && !$user->created_by) {
            $user->created_by = auth()->id();
        }
    }

    public function updated(User $user)
    {
        // $user->authenticable->touch();
    }
}