<?php

namespace App\Domain\Model\Documents\Contact;

use League\Fractal;

class ContactTransformer extends Fractal\TransformerAbstract
{
    public function transform(Contact $contact)
    {
        return [
            'uuid' => $contact->uuid,

            // Profile
            'first_name' => $contact->first_name,
            'last_name' => $contact->last_name,
            'email' => $contact->email,
            'phone' => $contact->phone,
            'website' => $contact->website,
            'job_title' => $contact->job_title
        ];
    }
}