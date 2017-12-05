<?php

namespace App\Domain\Service\Auth;

use App\Domain\Constants\Permission\Actions as PermissionAction;
use App\Domain\Constants\Permission\Scopes as PermissionScope;
use App\Domain\Model\Authentication\Account\Account;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Model\Authorization\Permission\Permission;
use App\Domain\Model\Documents\Client\Client;

class AuthorizationService
{
    protected $permissibleTypes;

    public function __construct()
    {
        $this->permissibleTypes = $this->getPermissibleTypes();
    }

    public function givePermissionToRole(Role $role, $action, $permissibleType, $scope, $scopeId)
    {
        if (is_array($action)) {
            $ret = [];

            foreach ($action as $a) {
                $ret[$a] = $this->givePermissionToRole($role, $a, $permissibleType, $scope, $scopeId);
            }

            return $ret;
        }

        $this->transformScopeAndAction($scope, $action);

        $permissible = $this->getPermissible($scope, $scopeId, $permissibleType);

        // trying to set permission on non-existent document
        if ($permissible === false) {
            return false;
        }

        // If role already has this permission, do nothing
        if ($permissible instanceof AbstractDocument) {
            if ($role->hasPermissionTo($action, $permissible)) {
                return true;
            }
        }
        else {
            $scopeable = $this->getScopeable($scope, $scopeId);

            if (!$scopeable) {
                return false; // invalid scope
            }

            if ($role->hasPermissionTo($action, $permissible, $scopeable)) {
                return true; // already has permission for this action
            }
        }


        // if we are logged in, check if we have the permission to give this permission for this role
        // if ($checkAuth && auth()->check() && !$this->canUserManageThisPermission(auth()->user(), $action, $permissible, $scopeable ?? null)) {
        //     return false;
        // }

        // create a new permission for role
        return $role->permissions()->create([
            'scope' => $scope,
            'scope_id' => $scopeId,
            'permissible_type' => $permissibleType ? resource_name($permissibleType) : null,
            'permission_type_id' => $action
        ]);
    }

    public function revokePermissionFromRole(Role $role, $action, $permissibleType, $scope, $scopeId)
    {
        if (is_array($action)) {
            $ret = [];

            foreach ($action as $a) {
                $ret[$a] = $this->revokePermissionFromRole($role, $a, $permissibleType, $scope, $scopeId);
            }

            return $ret;
        }

        $this->transformScopeAndAction($scope, $action);

        $permissible = $this->getPermissible($scope, $scopeId, $permissibleType);

        // trying to set permission on non-existent document
        if ($permissible === false) {
            return false;
        }

        // If role already has no such permission, do nothing
        if ($permissible instanceof AbstractDocument) {
            if (!$role->hasPermissionTo($action, $permissible)) {
                return true;
            }
        }
        else {
            $scopeable = $this->getScopeable($scope, $scopeId);

            if (!$scopeable) {
                return false; // invalid scope
            }

            if (!$role->hasPermissionTo($action, $permissible, $scopeable)) {
                return true; // already has no permission for this action
            }
        }

        // if we are logged in, check if we have the permission to give this permission
        if (auth()->check() && !$this->canUserManageThisPermission(auth()->user(), $action, $permissible, $scopeable ?? null)) {
            return false;
        }

        $foundPermissions = $role->permissions()
            ->can($action, $permissible, $scopeable ?? null)
            // add check for same scope as the one we're trying to remove,
            // so we won't end up removing account level permission if user
            // sends request to remove company level permission.
            ->where('scope', $scope)
            ->get()
            ->pluck('id');

        // revoke permission from user
        $role->permissions()->detach($foundPermissions);
        return true;
    }

    /**
     * Give user permission
     */
    public function givePermissionToUser(User $user, $action, $permissibleType, $scope, $scopeId)
    {
        return $this->givePermissionToRole($user->role, $action, $permissibleType, $scope, $scopeId);
    }

    /**
     * Revoke user permission
     */
    public function revokePermissionFromUser(User $user, $action, $permissibleType, $scope, $scopeId)
    {
        return $this->revokePermissionFromRole($user->role, $action, $permissibleType, $scope, $scopeId);
    }

    public function giveRoleToUser(User $user, Role $role)
    {
        if (auth()->check()) {
            $authUser = auth()->user();

            /**
             * User can manage user
             */
            if (!$authUser->hasPermissionTo(PermissionAction::MANAGE, $user)) {
                return false;
            }

            /**
             * Has same or better role
             */
            if (!$authUser->hasSameOrBetterRole($role)) {
                return false;
            }
        }

        /**
         * User already has this role
         */
        if ($user->hasSameOrBetterRole($role)) {
            return true;
        }

        /**
         * Attach new role to the user
         */
        $user->roles()->attach($role->uuid);

        return true;
    }


    public function revokeRoleFromuser(User $user, Role $role)
    {
        if (auth()->check()) {
            $authUser = auth()->user();

            /**
             * User can manage user
             */
            if (!$authUser->hasPermissionTo(PermissionAction::MANAGE, $user)) {
                return false;
            }

            /**
             * Has same or better role
             */
            if (!$authUser->hasSameOrBetterRole($role)) {
                return false;
            }
        }

        /**
         * User doesn't have such role
         */
        if (!$user->roles()->find($role->uuid)->exists()) {
            return true;
        }

        /**
         * Detach role from the user
         */
        $user->roles()->detach($role->uuid);
        return true;
    }

    public function roleHasPermission(Role $role, $action, $permissibleType, $scope, $scopeId)
    {
        $this->transformScopeAndAction($scope, $action);

        $permissible = $this->getPermissible($scope, $scopeId, $permissibleType);

        // trying to set permission on non-existent document
        if ($permissible === false) {
            return false;
        }

        if ($permissible instanceof AbstractDocument) {
            if ($role->hasPermissionTo($action, $permissible)) {
                return true;
            }
        }
        else {
            $scopeable = $this->getScopeable($scope, $scopeId);

            if (!$scopeable) {
                return false; // invalid scope
            }

            if ($role->hasPermissionTo($action, $permissible, $scopeable)) {
                return true;
            }
        }
        return true;
    }

    public function userHasPermission(User $user, $action, $permissibleType, $scope, $scopeId)
    {
        return $this->roleHasPermission($user->role, $action, $permissibleType, $scope, $scopeId);
    }

    public function getPermissible($scope, $scopeId, $permissibleType)
    {
        if ($scope === PermissionScope::DOCUMENT) {
            $permissibleClass = $this->getPermissibleClass($permissibleType);
            if ($document = $permissibleClass::find($scopeId)) {
                return $document;
            }
            return false;
        }
        return $permissibleType;
    }

    public function getPermissibleClass($permissibleType)
    {
        return in_array($permissibleType, $this->permissibleTypes)
            ? $this->permissibleTypes[$permissibleType]
            : $permissibleType;
    }

    public function canUserManageThisPermission(User $user, $action, $permissible, $scopeable)
    {
        // check if user can manage selected permissible
        if (!$user->hasPermissionTo(PermissionAction::MANAGE, $permissible, $scopeable)) {
            return false;
        }
        // BUT THAT'S NOT ALL
        // it is actually, MANAGE permission should be enough for now
        return true;

        /**
         * First, convert permissible type to actual class of the document
         */
        try {
            $permissibleClass = $this->getPermissibleClass($permissibleType);
        } catch (\OutOfBoundsException $e) {
            return false;
        }

        $scopeIds = null;

        /**
         * And second, we need to check if user can give this permission.
         *
         * If he's trying to give an account scope level permission to create
         * products without being able to do that himself, stop this criminal scum.
         */
        if ($scope === PermissionScope::ACCOUNT) {
            $scopeIds = [
                PermissionScope::ACCOUNT => $user->account_uuid
            ];
        }

        /**
         * Same goes for company level permission. He's able to only manage
         * few selected clients and he's giving permission to for someone
         * to be able delete any client on the company? Big no no.
         */
        if ($scope === PermissionScope::COMPANY) {
            $companyId = $user->companies()->first()->uuid;

            /**
             * User is trying to give permission to manage documents of company
             * he's not part of.
             */
            if ($scopeId !== $companyId) {
                return false;
            }

            $scopeIds = [
                // we're passing account uuid, because someone in control of
                // whole account clients should be able to give permission with
                // scope of company
                PermissionScope::ACCOUNT => $user->account_uuid,
                PermissionScope::COMPANY => $companyId
            ];
        }

        // if scope is document, get instance of that document
        if ($scope === PermissionScope::DOCUMENT) {
            $permissible = $permissibleClass::find($scopeId);

            if (!$permissible) {
                return false;
            }
        } else {
            // if scope isn't document, instead of instance simply pass the type of permissible
            $permissible = $permissibleType;
        }

        $foundPermissions = Permission::ofUser($user)->can($action, $permissible, $scopeIds)->get();

        foreach ($foundPermissions as $permission) {
            // the lower value scope has, the higher level it is
            if ($permission->scope <= $scope) {
                $foundPermission = $permission;
                break;
            }
        }

        /**
         * Couldn't find appropriate permission
         */
        if (!isset($foundPermission)) {
            return false;
        }
        return true;
    }

    private function getScopeable($scope, $scopeId)
    {
        if ($scope === PermissionScope::ACCOUNT) {
            return Account::find($scopeId);
        }
        if ($scope === PermissionScope::COMPANY) {
            return Company::find($scopeId);
        }
        if ($scope === PermissionScope::CLIENT) {
            return Client::find($scopeId);
        }
        return null;
    }

    public function transformScopeAndAction(&$scope, &$action)
    {
        // Convert scope and action from names to id's
        if (!is_numeric($scope) && $scope) {
            $scope = PermissionScope::getByName($scope);
        }
        if (!is_numeric($action) && $action) {
            $action = PermissionAction::getByName($action);
        }
    }

    private function getPermissibleTypes()
    {
        return array_reduce([
            \App\Domain\Model\Documents\Client\Client::class,
            \App\Domain\Model\Documents\Credit\Credit::class,
            \App\Domain\Model\Documents\Expense\Expense::class,
            \App\Domain\Model\Documents\Expense\ExpenseCategory::class,
            \App\Domain\Model\Documents\Product\Product::class,
            \App\Domain\Model\Documents\Vendor\Vendor::class,
            \App\Domain\Model\Documents\Payment\Payment::class,
            \App\Domain\Model\Documents\Invoice\Invoice::class,
            \App\Domain\Model\Documents\RecurringInvoice\RecurringInvoice::class,
            \App\Domain\Model\Documents\Quote\Quote::class,
            \App\Domain\Model\Documents\TaxRate\TaxRate::class,
            \App\Domain\Model\CRM\Project\Project::class,
            \App\Domain\Model\CRM\TaskList\TaskList::class,
            \App\Domain\Model\CRM\Task\Task::class,
            \App\Domain\Model\Documents\Employee\Employee::class
        ], function ($map, $className) {
            $map[resource_name($className)] = $className;
            return $map;
        }, []);
    }
}