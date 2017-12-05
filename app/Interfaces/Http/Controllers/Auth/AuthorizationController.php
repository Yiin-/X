<?php

namespace App\Interfaces\Http\Controllers\Auth;

use App\Interfaces\Http\Controllers\AbstractController;
use App\Domain\Service\Auth\AuthorizationService;
use App\Domain\Service\Documents\DocumentsService;
use App\Domain\Model\Authorization\Permission\Permission;

class AuthorizationController extends AbstractController
{
    public function __construct(AuthorizationService $authorizationService, DocumentsService $documentsService)
    {
        $this->authorizationService = $authorizationService;
        $this->documentsService = $documentsService;
    }

    public function accessibleDocuments()
    {
        $permissions = array_map(function ($data) {
            $scope = $data['scope'];
            $action = $data['action'];

            $this->authorizationService->transformScopeAndAction($scope, $action);

            $permission = new Permission;

            $permission->action = $action;
            $permission->permissible_type = $data['permissible_type'];
            $permission->scope = $scope;
            $permission->scope_id = $data['scope_id'];

            return $permission;
        }, array_filter(request()->get('permissions', []), function ($data) {
            $action = $data['action'];
            $permissibleType = $data['permissible_type'];
            $scope = $data['scope'];
            $scopeId = $data['scope_id'];

            return $this->authorizationService->userHasPermission(auth()->user(), $action, $permissibleType, $scope, $scopeId);
        }));

        $data = [];

        foreach ($this->documentsService->getRepositories() as $repository) {
            $documents = $repository->getUsingPermissions($permissions);

            if (count($documents)) {
                $data[resource_name($repository->getDocumentClass())] = $documents;
            }
        }

        return $data;
    }

    /**
     * Give permission to role
     */
    public function givePermissionToRole(Role $role)
    {
        // scope of the permission
        $scope = request()->get('scope');
        // id of the scope
        $scopeId = request()->get('scopeId');
        // permission type (view|update|...)
        $action = request()->get('action');
        // document resource name (product|client|invoice|...)
        $permissibleType = request()->get('permissibleType');

        if ($this->authorizationService->givePermissionToRole($role, $action, $permissibleType, $scope, $scopeId)) {
            $role->load(['permissions']);
            return response()->json($role, 200);
        } else {
            return response(null, 401);
        }
    }

    /**
     * Revoke role permission from role
     */
    public function revokePermissionFromRole(Role $role)
    {
        // scope of the permission
        $scope = request()->get('scope');
        // id of the scope
        $scopeId = request()->get('scopeId');
        // permission type (view|update|...)
        $action = request()->get('action');
        // document resource name (product|client|invoice|...)
        $permissibleType = request()->get('permissibleType');

        if ($this->authorizationService->revokePermissionFromRole($role, $scope, $scopeId, $action, $permissibleType)) {
            $role->load(['permissions']);
            return response()->json($role, 200);
        } else {
            return response(null, 401);
        }
    }

    /**
     * Give permission to user
     */
    public function givePermissionToUser(User $user)
    {
        // scope of the permission
        $scope = request()->get('scope');
        // id of the scope
        $scopeId = request()->get('scopeId');
        // permission type (view|update|...)
        $action = request()->get('action');
        // document resource name (product|client|invoice|...)
        $permissibleType = request()->get('permissibleType');

        if ($this->authorizationService->givePermissionToUser($user, $action, $permissibleType, $scope, $scopeId)) {
            $user->load(['employee']);
            return response()->json($user, 200);
        } else {
            return response(null, 401);
        }
    }

    /**
     * Revoke user permission from user
     */
    public function revokePermissionFromUser(User $user)
    {
        // scope of the permission
        $scope = request()->get('scope');
        // id of the scope
        $scopeId = request()->get('scopeId');
        // permission type (view|update|...)
        $action = request()->get('action');
        // document resource name (product|client|invoice|...)
        $permissibleType = request()->get('permissibleType');

        if ($this->authorizationService->revokePermissionFromUser($user, $action, $permissibleType, $scope, $scopeId)) {
            $user->load(['employee']);
            return response()->json($user, 200);
        } else {
            return response(null, 401);
        }
    }

    /**
     * Give role to user
     */
    public function giveRoleToUser(User $user, Role $role)
    {
        if ($this->authorizationService->giveRoleToUser($user, $role)) {
            $user->load(['employee']);
            return response()->json($user, 200);
        } else {
            return response(null, 401);
        }
    }

    /**
     * Revoke role from user
     */
    public function revokeRoleFromUser(User $user, Role $role)
    {
        if ($this->authorizationService->revokeRoleFromuser($user, $role)) {
            $user->load(['employee']);
            return response()->json($user, 200);
        } else {
            return response(null, 401);
        }
    }
}