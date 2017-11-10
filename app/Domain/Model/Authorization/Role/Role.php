<?php

namespace App\Domain\Model\Authorization\Role;

use App\Domain\Model\Documents\Shared\AbstractDocument;

use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authorization\Permission\Permission;

class Role extends AbstractDocument
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'company_uuid',
        'parent_role_uuid'
    ];

    protected $dispatchesEvents = [];

    public function getTransformer()
    {
        return new RoleTransformer;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}