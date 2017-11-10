<?php

namespace App\Domain\Model\Authorization\Permission;

use League\Fractal;

class RoleTransformer extends Fractal\TransformerAbstract
{
    public function transform(Permission $permission)
    {
        return [
            'type' => $permission->type
        ];
    }
}