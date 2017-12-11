<?php

namespace App\Domain\Model\Authentication\User;

use League\Fractal;
use App\Domain\Model\Documents\Employee\EmployeeTransformer;
use App\Domain\Model\Authentication\Company\CompanyTransformer;
use App\Domain\Model\Authorization\Role\RoleTransformer;

class UserTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'roles'
    ];

    public function transform(User $user)
    {
        return [
            'uuid' => $user->uuid,
            'companies' => $user->companies->pluck('uuid'),

            // User private data
            'settings' => $user->settings,
            'preferences' => $user->preferences,
            'state' => json_decode($user->state),

            // Whos user is this
            'authenticable_type' => resource_name($user->authenticable_type),
            'authenticable_id' => $user->authenticable_id,

            // Access scopes
            'assign_all_countries' => $user->assign_all_countries,
            'countries' => $user->countries->pluck('id'),

            'assign_all_clients' => $user->assign_all_clients,
            'clients' => $user->clients->pluck('uuid'),

            'is_disabled' => $user->is_disabled
        ];
    }

    public function includeRoles(User $user)
    {
        $roles = $user->roles;

        return $this->collection($roles, new RoleTransformer);
    }
}