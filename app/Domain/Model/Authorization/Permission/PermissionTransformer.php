<?php

namespace App\Domain\Model\Authorization\Permission;

use League\Fractal;
use App\Domain\Constants\Permission\Actions as PermissionAction;
use App\Domain\Constants\Permission\Scopes as PermissionScope;

class PermissionTransformer extends Fractal\TransformerAbstract
{
    public function transform(Permission $permission)
    {
        return [
            'id' => $permission->id,
            'scope' => PermissionScope::getById($permission->scope),
            'scope_id' => $permission->scope_id,
            'permissible_type' => $permission->permissible_type ?? '*',
            'action' => PermissionAction::getById($permission->permission_type_id)
        ];
    }
}