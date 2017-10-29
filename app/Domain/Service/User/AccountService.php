<?php

namespace App\Domain\Service\User;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Service\Passive\PassiveDataService;
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
use Ramsey\Uuid\Uuid;

class AccountService
{
    protected $accountRepository;
    protected $profileRepository;
    protected $userRepository;
    protected $companyRepository;
    protected $roleRepository;

    protected $passiveDataService;
    protected $documentsService;
    protected $crmService;
    protected $featuresService;

    public function __construct(
        AccountRepository $accountRepository,
        ProfileRepository $profileRepository,
        UserRepository $userRepository,
        CompanyRepository $companyRepository,
        RoleRepository $roleRepository,

        PassiveDataService $passiveDataService,
        DocumentsService $documentsService,
        CrmService $crmService,
        SystemService $systemService
        // FeaturesService $featuresService
    ) {
        $this->accountRepository = $accountRepository;
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
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
         * Create profile for the user
         * @var \App\Domain\Model\Documents\Profile\Profile
         */
        $profile = $this->profileRepository->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $userEmail
        ]);

        /**
         * Create a new user to manage created account
         * @var \App\Domain\Model\Authentication\User\User
         */
        \Log::debug('Creating user account: ' . $userEmail);
        $user = $this->userRepository->create([
            'username' => $userEmail,
            'password' => $userPassword
        ], [
            'profile_uuid' => $profile->uuid,
            'account_uuid' => $account->uuid,
            'guest_key' => $guestKey,
            'confirmation_token' => str_random(32)
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
        // foreach (
        //     [
        //         Client::class,
        //         Credit::class,
        //         Expense::class,
        //         ExpenseCategory::class,
        //         Product::class,
        //         Vendor::class,
        //         Payment::class,
        //         Invoice::class,
        //         RecurringInvoice::class,
        //         Quote::class,
        //         TaxRate::class,
        //         Project::class,
        //         TaskList::class,
        //         Task::class,
        //     ] as $documentClass
        // ) {
        //     foreach (Actions::LIST as $action) {
        //         $rootRole->permissions()->create([
        //             'type' => $action,
        //             'permissible_type' => $documentClass
        //         ]);
        //     }
        // }

        /**
         * Assign role to user
         */
        $user->roles()->attach($rootRole->uuid);

        /**
         * Set default settings for user
         */
        $user->settings()->create([
            'currency_code' => Currency::whereCode('EUR')->first()->code,
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

        /**
         * Return created user
         */
        return $user;
    }

    public function fetchDataForUser($user = null)
    {
        return [
            'passive' => $this->passiveDataService->getAll(),
            'documents' => $this->documentsService->getAll($user),
            'crm' => $this->crmService->getAll($user),
            'system' => $this->systemService->getAll($user)
            // 'features' => $this->featuresService->getAll($user)
        ];
    }
}