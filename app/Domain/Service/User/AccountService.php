<?php

namespace App\Domain\Service\User;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\Account\AccountRepository;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Credit\Credit;
use App\Domain\Model\Documents\Expense\Expense;
use App\Domain\Model\Documents\ExpenseCategory\ExpenseCategory;
use App\Domain\Model\Documents\Product\Product;
use App\Domain\Model\Documents\Payment\Payment;
use App\Domain\Model\Documents\Vendor\Vendor;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\RecurringInvoice\RecurringInvoice;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Model\Documents\TaxRate\TaxRate;
use App\Domain\Model\Documents\Project\Project;
use App\Domain\Model\Documents\TaskList\TaskList;
use App\Domain\Model\Documents\Task\Task;
use App\Domain\Model\Documents\Profile\ProfileRepository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Authentication\Company\CompanyRepository;
use App\Domain\Model\Authorization\Role\RoleRepository;

class AccountService
{
    protected $accountRepository;
    protected $profileRepository;
    protected $userRepository;
    protected $companyRepository;
    protected $roleRepository;

    public function __construct(
        AccountRepository $accountRepository,
        ProfileRepository $profileRepository,
        UserRepository $userRepository,
        CompanyRepository $companyRepository,
        RoleRepository $roleRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
        $this->roleRepository = $roleRepository;
    }

    public function createNewAccount(
        $companyName,
        $companyEmail,
        $siteAddress,
        $firstName,
        $lastName,
        $userEmail,
        $userPassword
    ) {
        /**
         * Create a new account for the user.
         * @var \App\Domain\Model\Authentication\Account\Account
         */
        $account = $this->accountRepository->create([
            'name' => $companyName,
            'site_address' => $siteAddress
        ]);

        /**
         * Create profile for the user
         * @var \App\Domain\Model\Documents\Profile\Profile
         */
        $profile = $this->profileRepository->create([
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);

        /**
         * Create a new user to manage created account
         * @var \App\Domain\Model\Authentication\User\User
         */
        $user = $this->userRepository->create([
            'username' => $userEmail,
            'password' => $userPassword
        ], [
            'profile_uuid' => $profile->uuid,
            'account_uuid' => $account->uuid
        ]);

        /**
         * Create a new company for the account
         * @var \App\Domain\Model\Authentication\Company\Company
         */
        $company = $this->companyRepository->create([
            'name' => $companyName,
            'email' => $companyEmail
        ], [
            'account_uuid' => $account->uuid
        ]);

        /**
         * Assign user to newly created company
         */
        $user->companies()->attach($company->uuid);

        /**
         * Create company root role
         */
        $rootRole = $this->roleRepository->create([
            'name' => $companyName
        ], [
            'company_uuid' => $company->uuid
        ]);

        /**
         * Create permissions to manage all company documents
         */
        foreach (
            [
                Client::class,
                Credit::class,
                Expense::class,
                ExpenseCategory::class,
                Product::class,
                Vendor::class,
                Payment::class,
                Invoice::class,
                RecurringInvoice::class,
                Quote::class,
                TaxRate::class,
                Project::class,
                TaskList::class,
                Task::class,
            ] as $documentClass
        ) {
            foreach (Actions::LIST as $action) {
                $rootRole->permissions()->create([
                    'type' => $action,
                    'permissible_type' => $documentClass
                ]);
            }
        }

        /**
         * Assign role to user
         */
        $user->roles()->attach($rootRole->uuid);

        /**
         * Return created user
         */
        return $user;
    }
}