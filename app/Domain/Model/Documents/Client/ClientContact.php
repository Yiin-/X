<?php

namespace App\Domain\Model\Documents\Client;

use App\Domain\Model\Documents\Profile\Profile;
use App\Domain\Model\Documents\Shared\AbstractDocument;

class ClientContact extends AbstractDocument
{
    public function transform()
    {
        return [
            // ID
            'uuid' => $this->uuid,

            // Profile
            'first_name' => $this->profile->first_name,
            'last_name' => $this->profile->last_name,
            'email' => $this->profile->email,
            'phone' => $this->profile->phone,
            'job_position' => $this->profile->job_position
        ];
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}