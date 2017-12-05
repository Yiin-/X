<?php

namespace App\Domain\Model\Authorization\Role;

use League\Fractal;
use App\Domain\Model\Authorization\Permission\PermissionTransformer;

class RoleTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'permissions'
    ];

    public function excludeForBackup()
    {
        return ['permissions'];
    }

    public function transform(Role $role)
    {
        return [
            'uuid' => $role->uuid,

            'name' => $role->name,

            'created_at' => $role->created_at,
            'updated_at' => $role->updated_at
        ];
    }

    public function includePermissions(Role $role)
    {
        return $this->collection($role->permissions, new PermissionTransformer);
    }
}