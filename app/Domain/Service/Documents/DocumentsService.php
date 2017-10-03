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
        $this->taxRateRepository = $taxRateRepository;
    }

    public function getAll($user = null)
    {
        $arr = [];

        foreach ([
            'clientRepository',
            'creditRepository',
            'expenseRepository',
            'invoiceRepository',
            'paymentRepository',
            'productRepository',
            'quoteRepository',
            'recurringInvoiceRepository',
            'vendorRepository',
            'taxRateRepository'
        ] as $repo) {
            $arr [resource_name($this->{$repo}->getDocumentClass())] = $this->{$repo}->getVisible($user ? $user->uuid : auth()->id());
        }
        return $arr;
    }
}