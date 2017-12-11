<?php

namespace App\Domain\Model\Authorization\Role;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Constants\Permission\Scopes as PermissionScope;
use App\Domain\Service\Auth\AuthorizationService;

class RoleRepository extends AbstractDocumentRepository
{
    protected $repository;

    public function __construct(AuthorizationService $authorizationService)
    {
        $this->repository = new Repository(Role::class);
        $this->authorizationService = $authorizationService;
    }

    public function fillDefaultData(&$data, &$protectedData)
    {
        if (!isset($protectedData['roleable_type'])) {
            $protectedData['roleable_type'] = Company::class;
            $protectedData['roleable_id'] = $protectedData['company_uuid'] ?? current_company()->uuid;
        }
    }

    public function saved(Role $role, &$data, &$protectedData)
    {
        if (isset($data['permissions'])) {
            /**
             * Remove existing permissions
             */
            $role->permissions()->detach(
                $role->permissions()
                    ->where('scope', PermissionScope::COMPANY)
                    ->where('scope_id', $role->roleable_id)
                    ->get(['permissions.id'])
                    ->pluck('id')
            );

            /**
             * Set new
             */
            foreach ($data['permissions'] as $permission) {
                $this->authorizationService->givePermissionToRole(
                    $role,
                    $permission['action'],
                    $permission['permissible_type'],
                    $permission['scope'],
                    $permission['scope_id']
                );
            }
        }
        $role->touch();
    }
}