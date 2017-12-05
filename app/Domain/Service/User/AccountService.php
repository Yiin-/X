<?php

namespace App\Domain\Service\User;

use App\Domain\Constants\Permission\Actions as PermissionAction;
use App\Domain\Constants\Permission\Scopes as PermissionScope;
use App\Domain\Service\Passive\PassiveDataService;
use App\Domain\Service\Auth\AuthorizationService;
use App\Domain\Service\Documents\DocumentsService;
use App\Domain\Service\CRM\CrmService;
use App\Domain\Service\System\SystemService;
use App\Domain\Service\Features\FeaturesService;
use App\Domain\Model\Authentication\Account\AccountRepository;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Credit\Credit;
use App\Domain\Model\Documents\Expense\Expense;
use App\Domain\Model\Documents\Expense\ExpenseCategory;
use App\Domain\Model\Documents\Product\Product;
use App\Domain\Model\Documents\Payment\Payment;
use App\Domain\Model\Documents\Vendor\Vendor;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\RecurringInvoice\RecurringInvoice;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Model\Documents\TaxRate\TaxRate;
use App\Domain\Model\Documents\Employee\Employee;
use App\Domain\Model\CRM\Project\Project;
use App\Domain\Model\CRM\TaskList\TaskList;
use App\Domain\Model\CRM\Task\Task;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authentication\Company\CompanyRepository;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Model\Authorization\Role\RoleRepository;
use Ramsey\Uuid\Uuid;

class AccountService
{
    protected $accountRepository;
    protected $userRepository;
    protected $companyRepository;
    protected $roleRepository;

    protected $passiveDataService;
    protected $documentsService;
    protected $crmService;
    protected $featuresService;

    public function __construct(
        AccountRepository $accountRepository,
        UserRepository $userRepository,
        CompanyRepository $companyRepository,
        AuthorizationService $authorizationService,
        RoleRepository $roleRepository,

        PassiveDataService $passiveDataService,
        DocumentsService $documentsService,
        CrmService $crmService,
        SystemService $systemService
        // FeaturesService $featuresService
    ) {
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
        $this->authorizationService = $authorizationService;
        $this->roleRepository = $roleRepository;

        $this->passiveDataService = $passiveDataService;
        $this->documentsService = $documentsService;
        $this->crmService = $crmService;
        $this->systemService = $systemService;
        // $this->featuresService = $featuresService;
    }

    public function createNewDemoAccount()
    {
        $guestKey = (string)Uuid::uuid5(Uuid::uuid4(), 'demo');

        $user = $this->createNewAccount(
            'demo', 'demo', $guestKey, 'demo', 'demo', 'demo', 'demo', $guestKey
        );

        return $guestKey;
    }

    public function createNewAccount(
        $companyName,
        $companyEmail,
        $siteAddress,
        $firstName,
        $lastName,
        $userEmail,
        $userPassword,
        $guestKey = null
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
         * Create a new user to manage created account
         * @var \App\Domain\Model\Authentication\User\User
         */
        \Log::debug('Creating user account: ' . $userEmail);

        $user = $this->createNewUser($company, $userEmail, $userPassword, $firstName, $lastName, $guestKey);

        /**
         * Create permission to manage account
         */
        $this->authorizationService->givePermissionToUser($user, null, null, PermissionScope::ACCOUNT, $account->uuid);

        /**
         * Return created user
         */
        return $user;
    }

    public function createUserForEmployee($employee, $password = null)
    {
        $user = $this->userRepository->create([
            'username' => $employee->email,
            'password' => $password,

            // employee info
            'employee_uuid' => $employee->uuid,

            // company info
            'company_uuid' => $employee->company_uuid
        ], [
            'account_uuid' => $employee->company->account_uuid,
            'confirmation_token' => str_random(32)
        ]);

        $this->setupUser($user);
        $this->authorizationService->givePermissionToUser($user, PermissionAction::VIEW, Employee::class, PermissionScope::COMPANY, $employee->company_uuid);

        return $user;
    }

    public function createNewUser($company, $email, $password, $firstName, $lastName, $guestKey = null)
    {
        $user = $this->userRepository->create([
            'username' => $email,
            'password' => $password,

            // employee info
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,

            // company info
            'company_uuid' => $company->uuid
        ], [
            'account_uuid' => $company->account_uuid,
            'guest_key' => $guestKey,
            'confirmation_token' => str_random(32)
        ]);

        $this->setupUser($user);

        return $user;
    }

    public function setupUser($user)
    {
        /**
         * Set default settings for user
         */
        $user->settings()->create([
            'currency_code' => 'EUR',
            'locale' => 'en'
        ]);

        /**
         * Default user preferences
         */
        $user->preferences()->createMany([
            [
                'key' => 'date_format',
                'value' => 'M j, Y'
            ],
            [
                'key' => 'invoice_number_pattern',
                'value' => '{counter}',
            ],
            [
                'key' => 'recurring_invoice_number_pattern',
                'value' => 'R{counter}'
            ],
            [
                'key' => 'quote_number_pattern',
                'value' => 'Q{counter}'
            ]
        ]);
    }

    public function fetchDataForUser($user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        return [
            'passive' => $this->passiveDataService->getAll(),
            'documents' => $this->documentsService->getAll($user),
            'crm' => $this->crmService->getAll($user),
            'system' => $this->systemService->getAll($user),
            'companies' => $user->companies->map(function ($company) {
                return $company->transform();
            })
            // 'features' => $this->featuresService->getAll($user)
        ];
    }
}