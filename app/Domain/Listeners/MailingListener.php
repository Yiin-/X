<?php

namespace App\Domain\Listeners;

use App\Domain\Events\Authentication\EmployeeUserWasCreated;
use App\Domain\Mail\WelcomeMessageForUser;
use App\Domain\Mail\InvitationMessageForUser;
use App\Domain\Model\Documents\Employee\Employee;
use App\Domain\Model\Authentication\User\User;
use Mail;

class MailingListener
{
    public function subscribe($events)
    {
        $events->listen(EmployeeUserWasCreated::class, self::class . '@employeeUserWasCreated');
    }

    /**
     * A new user account for employee was created.
     */
    public function employeeUserWasCreated(EmployeeUserWasCreated $event)
    {
        if ($event->user) {
            $this->sendWelcomeEmail($event->user);
        }
    }

    /**
     * Send welcome email to user after his registration
     */
    protected function sendWelcomeEmail(User $user)
    {
        if ($user->guest_key) {
            // Ignore demo accounts
            return;
        }

        if ($user->created_by) {
            // User was created by someone,
            // send invitation email.
            $this->sendInvitationEmail($user);
            return;
        }

        \Log::debug('Sending welcome message to ' . $user->email);
        Mail::to($user->email)
            ->send(new WelcomeMessageForUser($user));
    }

    /**
     * Send invitation with account information
     * for the user.
     */
    protected function sendInvitationEmail(User $user)
    {
        if (!$user->created_by) {
            // Only send invitation emails to
            // users that were created as employees.
            return;
        }

        \Log::debug('Sending invitation message to ' . $user->email);
        Mail::to($user->email)
            ->send(new InvitationMessageForUser($user));
    }
}