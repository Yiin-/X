<?php

namespace App\Domain\Model\Documents\Profile;

use League\Fractal;

class ProfileTransformer extends Fractal\TransformerAbstract
{
    public function transform(Profile $profile)
    {
        return [
            'uuid' => $profile->uuid,

            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'email' => $profile->email,
            'website' => $profile->website,
            'phone' => $profile->phone,
            'job_position' => $profile->job_position
        ];
    }
}