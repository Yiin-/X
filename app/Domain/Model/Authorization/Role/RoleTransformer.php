<?php

namespace App\Domain\Model\Authorization\Role;

use League\Fractal;

class RoleTransformer extends Fractal\TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'uuid' => $role->uuid,

            'name' => $role->name
        ];
    }
}