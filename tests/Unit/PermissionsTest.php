<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Application\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Service\Auth\AuthorizationService;
use App\Domain\Service\User\AccountService;
use App\Domain\Constants\Permission\Actions as PermissionAction;
use App\Domain\Constants\Permission\Scopes as PermissionScope;
use App\Domain\Model\Authentication\Account\Account;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Model\Authorization\Permission\Permission;
use App\Domain\Model\Documents\Employee\Employee;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Employee\EmployeeRepository;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function __construct()
    {
        parent::__construct();
    }

    public function testBasicTest()
    {
        $authorizationService = app(AuthorizationService::class);
        $accountService = app(AccountService::class);

        $rootUser = $accountService->createNewAccount(
            'test',
            'test',
            'test',
            'test',
            'test',
            'test',
            'test'
        );
        $account = $rootUser->account;
        $company = $rootUser->companies()->first();

        $this->assertTrue($rootUser->exists);
        $this->assertTrue($account->exists);
        $this->assertTrue($company->exists);

        $rootRole = $rootUser->role;

        $this->assertTrue($rootRole->permissions()->first()->permissible_type === null);

        $peasantRole = new Role;
        $peasantRole->forceFill([
            'uuid' => app(\App\Domain\Model\Authorization\Role\RoleRepository::class)->generateUuid(),
            'name' => 'peasant',
            'roleable_type' => $rootRole->roleable_type,
            'roleable_id' => $rootRole->roleable_id
        ]);
        $peasantRole = $rootRole->roles()->save($peasantRole);

        $peasantUser = factory(User::class)->make();
        $peasantUser->account_uuid = $account->uuid;
        $peasantUser->save();

        $personalRole = new Role;
        $personalRole->uuid = app(\App\Domain\Model\Authorization\Role\RoleRepository::class)->generateUuid();
        $peasantUser->role()->save($personalRole);
        $peasantUser->roles()->attach($personalRole->uuid);

        $peasantUser->companies()->attach($company->uuid);

        $authorizationService->giveRoleToUser($peasantUser, $peasantRole);
        $this->assertTrue($peasantUser->roles()->count() === 2);
        $authorizationService->revokeRoleFromUser($peasantUser, $peasantRole);
        $this->assertTrue($peasantUser->roles()->count() === 1);
        $authorizationService->giveRoleToUser($peasantUser, $peasantRole);

        $rootRole->permissions()->create([
            'scope' => PermissionScope::ACCOUNT,
            'scope_id' => $account->uuid,
            'permissible_type' => resource_name(Employee::class),
            'permission_type_id' => PermissionAction::MANAGE
        ]);

        $this->assertTrue(
            $rootRole->hasPermissionTo(PermissionAction::MANAGE, Employee::class, $account),
            'can root role manage employees on account level'
        );

        $this->actingAs($rootUser);

        $this->assertTrue(auth()->check());

        $authUser = auth()->user();

        $this->assertTrue(
            $authUser->hasPermissionTo(PermissionAction::MANAGE, Employee::class, $company),
            'can root user manage employees on company level'
        );

        $this->assertFalse(
            $peasantUser->hasPermissionTo(PermissionAction::CREATE, Client::class, $company),
            'can peasant user create clients on company level'
        );

        // give peasant user permission to create clients for specified company
        $permission = $authorizationService->givePermissionToRole($peasantRole,
            PermissionAction::CREATE,
            Client::class,
            PermissionScope::COMPANY,
            $company->uuid
        );

        $this->assertTrue($peasantRole->hasPermissionTo(
            PermissionAction::CREATE, Client::class, $company),
            'peasant role has permission to create clients on company level'
        );

        $this->assertTrue($peasantUser->hasPermissionTo(
            PermissionAction::CREATE, Client::class, $company),
            'peasnt user has permission to create clients on company level'
        );

        $authorizationService->revokePermissionFromRole($peasantRole,
            PermissionAction::CREATE,
            Client::class,
            PermissionScope::COMPANY,
            $company->uuid
        );

        $this->assertFalse($peasantUser->hasPermissionTo(
            PermissionAction::CREATE, Client::class, $company),
            'peasnt user should not have permission to create clients on company level'
        );

        $authorizationService->givePermissionToUser($peasantUser,
            PermissionAction::CREATE,
            Client::class,
            PermissionScope::COMPANY,
            $company->uuid
        );

        $this->assertFalse($peasantRole->hasPermissionTo(
            PermissionAction::CREATE, Client::class, $company),
            'peasnt role should not have permission to create clients on company level'
        );

        $this->assertTrue($peasantUser->hasPermissionTo(
            PermissionAction::CREATE, Client::class, $company),
            'peasnt user should have permission to create clients on company level'
        );
    }
}
