<?php

namespace App\Domain\Model\Documents\Employee;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class EmployeePolicy
{
    /**
     * Determine if user can view list of employees.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Employee::class);
    }

    /**
     * Determine if user can see given employee.
     *
     * @param  User $user
     * @param  Employee $employee
     * @return bool
     */
    public function see(User $user, Employee $employee)
    {
        return $user->hasPermissionTo(Actions::VIEW, $employee);
    }

    /**
     * Determine if user can create an employee.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Employee::class);
    }

    /**
     * Determine if the given employee can be updated by the user.
     *
     * @param  User   $user
     * @param  Employee $employee
     * @return bool
     */
    public function update(User $user, Employee $employee)
    {
        return $user->hasPermissionTo(Actions::EDIT, $employee);
    }

    /**
     * Determine if the given employee can be deleted by the user.
     *
     * @param  User   $user
     * @param  Employee $employee
     * @return bool
     */
    public function delete(User $user, Employee $employee)
    {
        return $user->hasPermissionTo(Actions::DELETE, $employee);
    }

    /**
     * Determine if the given employee can be archived by the user.
     *
     * @param  User   $user
     * @param  Employee $employee
     * @return bool
     */
    public function archive(User $user, Employee $employee)
    {
        return $user->hasPermissionTo(Actions::EDIT, $employee);
    }
}