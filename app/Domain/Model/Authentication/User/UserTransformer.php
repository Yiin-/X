<?php

namespace App\Domain\Model\Authentication\User;

use League\Fractal;
use App\Domain\Model\Documents\Employee\EmployeeTransformer;
use App\Domain\Model\Authentication\Company\CompanyTransformer;

class UserTransformer extends Fractal\TransformerAbstract
{
    // protected $availableIncludes = [
    //     'settings',
    //     'preferences',
    //     'state'
    // ];

    public function transform(User $user)
    {
        return [
            'uuid' => $user->uuid,
            'companies' => $user->companies->pluck('uuid'),

            'settings' => $user->settings,
            'preferences' => $user->preferences,
            'state' => json_decode($user->state),

            // We have authenticable both here and as include for a reason.
            // I don't remember exactly what reason, but it had something to do with
            // recursion that caused infinite loop when transforming user.
            'authenticable' => $user->authenticable->transform()->parseExcludes(['history', 'user'])->toArray(),
            'authenticable_type' => resource_name($user->authenticable_type),

            'is_disabled' => $user->is_disabled
        ];
    }
}