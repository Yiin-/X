<?php

namespace App\Domain\Events\Authorization;

use App\Domain\Model\Authorization\Role\Role;

class RoleWasSaved
{
    public $user;
    public $role;

    /**
     * Create a new event instance.
     *
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->user = auth()->user();
        $this->role = $role;
    }
}