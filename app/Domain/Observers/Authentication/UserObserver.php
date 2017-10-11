<?php

namespace App\Domain\Observers\Authentication;

use Mail;
use App\Domain\Mail\WelcomeMessageForUser;
use App\Domain\Model\Authentication\User\User;

class UserObserver
{
    public function created(User $user)
    {
        /**
         * Send welcome email to user after his registration
         */
        // Ignore demo accounts
        if (!$user->guest_key) {
            Mail::to($user->email)
                ->send(new WelcomeMessageForUser($user));
        }
    }
}