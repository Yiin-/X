<?php

namespace App\Domain\Model\Authentication\User;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Model\Authorization\Role\RoleRepository;

class UserRepository extends AbstractDocumentRepository
{
    protected $repository;
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->repository = new Repository(User::class);
        $this->roleRepository = $roleRepository;
    }

    public function adjustData(&$data, &$protectedData)
    {
        if ($data['password'] !== null) {
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        else {
            $password = null;
        }
        $protectedData = array_merge([
            'password' => $password
        ], $protectedData);
    }

    public function created(&$user, &$data)
    {
        /**
         * Create personal user role,
         * so we can assign some permissions directly to the user
         */
        $personalRole = new Role;
        $personalRole->uuid = $this->roleRepository->generateUuid();

        if (!$user->created_by) {
            $personalRole->name = 'Owner';
        }

        $user->role()->save($personalRole);
        $user->roles()->attach($personalRole->uuid);

        /**
         * Assign user to company
         */
        $user->companies()->attach($data['company_uuid']);

        /**
         * Assign this user to employee
         */
        if (isset($data['employee_uuid'])) {
            $employee = app(\App\Domain\Model\Documents\Employee\EmployeeRepository::class)->find($data['employee_uuid']);
        }
        else {
            $employee = app(\App\Domain\Model\Documents\Employee\EmployeeRepository::class)->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email']
            ], [
                'user_uuid' => $user->uuid,
                'company_uuid' => $data['company_uuid']
            ]);
        }

        $user->authenticable()->associate($employee)->save();

        $user->load(['authenticable']);
    }

    public function findByUsername($siteAddress, $username)
    {
        return $this->repository->newQuery()
                    ->when($username === 'demo', function ($query) use ($siteAddress) {
                        return $query->whereHas('account', function ($query) use ($siteAddress) {
                            $query->where('site_address', $siteAddress);
                        });
                    })
                    ->where('username', $username)
                    ->first();
    }

    public function findByConfirmationToken($token)
    {
        return $this->repository->newQuery()
            ->whereConfirmationToken($token)
            ->first();
    }

    public function findByInvitationToken($token)
    {
        return $this->repository->newQuery()
            ->whereInvitationToken($token)
            ->first();
    }
}