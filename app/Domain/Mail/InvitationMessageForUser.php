<?php

namespace App\Domain\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domain\Model\Authentication\User\User;

class InvitationMessageForUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $invitationToken;

    public $name;
    public $username;
    public $firstName;
    public $lastName;
    public $phone;
    public $email;
    public $jobTitle;

    public $companyName;
    public $companyEmail;
    public $inviter;
    public $isPasswordSet;

    public function __construct(User $user)
    {

        $this->user = $user;

        $company = $user->companies()->first();

        /**
         * Invitation token.
         */
        $this->invitationToken = $user->genInvitationToken();

        /**
         * Name of the person we're inviting
         */
        $this->name = $user->authenticable->first_name;

        /**
         * Filled personal data
         */
        $this->firstName = $user->authenticable->first_name;
        $this->lastName = $user->authenticable->last_name;
        $this->phone = $user->authenticable->phone;
        $this->email = $user->authenticable->email;
        $this->jobTitle = $user->authenticable->job_title;

        /**
         * Username
         */
        $this->username = $user->username;

        /**
         * Name of the company we're inviting user to.
         */
        $this->companyName = $company->name;

        /**
         * Email of the company.
         */
        $this->companyEmail = $company->email;

        /**
         * Name of the person who created this employee.
         */
        $this->inviter = $user->creator->authenticable->first_name;

        /**
         * Check if password for user is already set.
         */
        $this->isPasswordSet = $user->password !== null;
    }

    public function build()
    {
        return $this->markdown('emails.user.invitation');
    }
}