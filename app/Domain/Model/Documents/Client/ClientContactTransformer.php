<?php

namespace App\Domain\Model\Documents\Client;

use League\Fractal;

class ClientContactTransformer extends Fractal\TransformerAbstract
{
    public function transform(ClientContact $contact)
    {
        return [
            'uuid' => $contact->uuid,

            // Profile
            'first_name' => $contact->profile->first_name,
            'last_name' => $contact->profile->last_name,
            'email' => $contact->profile->email,
            'phone' => $contact->profile->phone,
            'job_position' => $contact->profile->job_position
        ];
    }
}