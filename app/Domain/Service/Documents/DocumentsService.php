<?php

namespace App\Domain\Service\Documents;

use App\Domain\Model\Documents\Client\ClientRepository;
use App\Domain\Model\Documents\Credit\CreditRepository;
use App\Domain\Model\Documents\Expense\ExpenseRepository;
use App\Domain\Model\Documents\Invoice\InvoiceRepository;
use App\Domain\Model\Documents\Payment\PaymentRepository;
use App\Domain\Model\Documents\Product\ProductRepository;
use App\Domain\Model\Documents\Quote\QuoteRepository;
use App\Domain\Model\Documents\RecurringInvoice\RecurringInvoiceRepository;
use App\Domain\Model\Documents\Vendor\VendorRepository;
use App\Domain\Model\Documents\TaxRate\TaxRateRepository;
use App\Domain\Model\Documents\Employee\EmployeeRepository;

class DocumentsService
{
    protected $clientRepository;
    protected $creditRepository;
    protected $expenseRepository;
    protected $invoiceRepository;
    protected $paymentRepository;
    protected $productRepository;
    protected $quoteRepository;
    protected $recurringInvoiceRepository;
    protected $vendorRepository;
    protected $employeeRepository;
    protected $taxRateRepository;

    public function __construct(
        ClientRepository $clientRepository,
        CreditRepository $creditRepository,
        ExpenseRepository $expenseRepository,
        InvoiceRepository $invoiceRepository,
        PaymentRepository $paymentRepository,
        ProductRepository $productRepository,
        QuoteRepository $quoteRepository,
        RecurringInvoiceRepository $recurringInvoiceRepository,
        VendorRepository $vendorRepository,
        EmployeeRepository $employeeRepository,
        TaxRateRepository $taxRateRepository
    ) {
        $this->clientRepository = $clientRepository;
        $this->creditRepository = $creditRepository;
        $this->expenseRepository = $expenseRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->paymentRepository = $paymentRepository;
        $this->productRepository = $productRepository;
        $this->quoteRepository = $quoteRepository;
        $this->recurringInvoiceRepository = $recurringInvoiceRepository;
        $this->vendorRepository = $vendorRepository;
        $this->employeeRepository = $employeeRepository;
        $this->taxRateRepository = $taxRateRepository;
    }

    public function getRepositories()
    {
        return [
            'clientRepository' => $this->clientRepository,
            'creditRepository' => $this->creditRepository,
            'expenseRepository' => $this->expenseRepository,
            'invoiceRepository' => $this->invoiceRepository,
            'paymentRepository' => $this->paymentRepository,
            'productRepository' => $this->productRepository,
            'quoteRepository' => $this->quoteRepository,
            'recurringInvoiceRepository' => $this->recurringInvoiceRepository,
            'vendorRepository' => $this->vendorRepository,
            'taxRateRepository' => $this->taxRateRepository
        ];
    }

    public function getAll($user = null)
    {
        $arr = [];

        foreach ($this->getRepositories() as $repo) {
            $arr [resource_name($repo->getDocumentClass())] = $repo->getVisible($user ? $user->uuid : auth()->id());
        }
        return $arr;
    }
}