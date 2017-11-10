<?php

namespace App\Domain\Model\Documents\Profile;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\User\User;

class Profile extends AbstractDocument
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'website',
        'phone',
        'job_position'
    ];

    public function getTransformer()
    {
        return new ProfileTransformer;
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}