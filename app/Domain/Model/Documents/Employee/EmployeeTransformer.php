<?php

namespace App\Domain\Model\Documents\Employee;

use League\Fractal;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;
use App\Domain\Model\Authentication\User\UserTransformer;
use App\Domain\Model\Authorization\Role\RoleTransformer;
use App\Domain\Model\Authorization\Permission\PermissionTransformer;

class EmployeeTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'history',
        'roles',
        'permissions',
        'user'
    ];

    public function excludeForBackup()
    {
        return ['history', 'user'];
    }

    public function transform(Employee $employee)
    {
        return [
            'uuid' => $employee->uuid,
            'company_uuid' => $employee->company_uuid,
            'companies' => $employee->auth ? $employee->auth->companies()->get(['uuid'])->pluck('uuid') : [],

            'profile_picture' => $employee->profile_picture,

            // Organization
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'job_title' => $employee->job_title,
            'email' => $employee->email,
            'phone' => $employee->phone,

            'is_disabled' => $employee->is_disabled,

            'created_at' => $employee->created_at,
            'updated_at' => $employee->updated_at,
            'archived_at' => $employee->archived_at,
            'deleted_at' => $employee->deleted_at
        ];
    }

    public function includeHistory(Employee $employee)
    {
        return $this->collection($employee->getHistory(), new ActivityTransformer);
    }

    public function includeRoles(Employee $employee)
    {
        $roles = [];

        if ($employee->auth) {
            $roles = $employee->auth->roles;
        }
        return $this->collection($roles, new RoleTransformer);
    }

    public function includePermissions(Employee $employee)
    {
        $permissions = [];

        if ($employee->auth) {
            $permissions = $employee->auth->role->permissions;
        }
        return $this->collection($permissions, new PermissionTransformer);
    }

    public function includeUser(Employee $employee)
    {
        if ($employee->auth) {
            return $this->item($employee->auth, new UserTransformer);
        }
        else {
            return null;
        }
    }
}