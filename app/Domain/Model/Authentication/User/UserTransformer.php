<?php

namespace App\Domain\Model\Authentication\User;

use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'uuid' => $user->uuid,

            'full_name' => $user->full_name,
            'email' => $user->email
        ];
    }
}