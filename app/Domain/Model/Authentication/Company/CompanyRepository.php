<?php

namespace App\Domain\Model\Authentication\Company;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Constants\Permission\Actions as PermissionAction;
use App\Domain\Constants\Permission\Scopes as PermissionScope;
use App\Domain\Service\Auth\AuthorizationService;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Model\Authorization\Role\RoleRepository;
use Auth;

class CompanyRepository extends AbstractDocumentRepository
{
    protected $repository;
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository, AuthorizationService $authorizationService)
    {
        $this->repository = new Repository(Company::class);
        $this->roleRepository = $roleRepository;
        $this->authorizationService = $authorizationService;
    }

    public function creating(&$data, &$protectedData)
    {
        if (!isset($protectedData['account_uuid']) && auth()->check()) {
            $protectedData['account_uuid'] = auth()->user()->account_uuid;
        }
    }

    public function created($company)
    {
        if (auth()->check()) {
            auth()->user()->companies()->attach($company->uuid);
            auth()->user()->touch();
        }

        $employeeRole = new Role;
        $employeeRole->uuid = $this->roleRepository->generateUuid();
        $employeeRole->name = 'Employee';

        $company->roles()->save($employeeRole);

        foreach ([
          'product',
          'client',
          'invoice',
          'payment',
          'credit',
          'quote',
          'expense',
          'vendor',
          'project',
          'employee' => [PermissionAction::VIEW]
        ] as $permissible => $actions) {
            $permissibleActions = [
                PermissionAction::VIEW,
                PermissionAction::CREATE,
                PermissionAction::EDIT,
                PermissionAction::DELETE
            ];

            if (is_integer($permissible)) {
                $permissible = $actions;
            } else {
                $permissibleActions = $actions;
            }

            $this->authorizationService->givePermissionToRole(
                $employeeRole, $permissibleActions, $permissible, PermissionScope::COMPANY, $company->uuid
            );
        }
    }
}