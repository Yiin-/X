<?php

namespace App\Domain\Model\Documents\Employee;

use League\Fractal;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;
use App\Domain\Model\Authentication\User\UserTransformer;
use App\Domain\Model\Authorization\Permission\PermissionTransformer;

class EmployeeTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'history',
        'user',
        'activity'
    ];

    public function excludeForBackup()
    {
        return ['history', 'user', 'activity'];
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

    public function includeUser(Employee $employee)
    {
        if ($employee->auth) {
            return $this->item($employee->auth, new UserTransformer);
        }
        else {
            return null;
        }
    }

    public function includeActivity(Employee $employee)
    {
        return $this->collection($employee->getActivity(), new ActivityTransformer);
    }
}