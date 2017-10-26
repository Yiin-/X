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

    public function transform()
    {
        return [
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'website' => $this->website,
            'phone' => $this->phone,
            'job_position' => $this->job_position
        ];
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}