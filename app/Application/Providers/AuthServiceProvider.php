<?php

namespace App\Application\Providers;

use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Client\ClientPolicy;
use App\Domain\Model\Documents\Expense\Expense;
use App\Domain\Model\Documents\Expense\ExpensePolicy;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\Invoice\InvoicePolicy;
use App\Domain\Model\Documents\RecurringInvoice\RecurringInvoice;
use App\Domain\Model\Documents\RecurringInvoice\RecurringInvoicePolicy;
use App\Domain\Model\Documents\Payment\Payment;
use App\Domain\Model\Documents\Payment\PaymentPolicy;
use App\Domain\Model\Documents\Product\Product;
use App\Domain\Model\Documents\Product\ProductPolicy;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Model\Documents\Quote\QuotePolicy;
use App\Domain\Model\Documents\TaxRate\TaxRate;
use App\Domain\Model\Documents\TaxRate\TaxRatePolicy;
use App\Domain\Model\Documents\Vendor\Vendor;
use App\Domain\Model\Documents\Vendor\VendorPolicy;
use App\Domain\Model\CRM\Project\Project;
use App\Domain\Model\CRM\Project\ProjectPolicy;
use App\Domain\Model\CRM\TaskList\TaskList;
use App\Domain\Model\CRM\TaskList\TaskListPolicy;
use App\Domain\Model\CRM\Task\Task;
use App\Domain\Model\CRM\Task\TaskPolicy;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Client::class => ClientPolicy::class,
        Credit::class => CreditPolicy::class,
        Expense::class => ExpensePolicy::class,
        Invoice::class => InvoicePolicy::class,
        RecurringInvoice::class => RecurringInvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        Product::class => ProductPolicy::class,
        Quote::class => QuotePolicy::class,
        TexRate::class => TexRatePolicy::class,
        Vendor::class => VendorPolicy::class,
        Project::class => ProjectPolicy::class,
        TaskList::class => TaskListPolicy::class,
        Task::class => TaskPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::ignoreMigrations();

        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addDays(15));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
