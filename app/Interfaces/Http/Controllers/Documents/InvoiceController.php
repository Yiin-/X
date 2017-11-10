<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Invoice\InvoiceRepository;
use App\Domain\Model\Documents\Credit\CreditRepository;
use App\Domain\Model\Documents\Credit\AppliedCredit;

class InvoiceController extends DocumentController
{
    protected $repository;

    public function __construct(
        InvoiceRepository $invoiceRepository,
        CreditRepository $creditRepository
    ) {
        $this->repository = $invoiceRepository;
        $this->creditRepository = $creditRepository;
    }

    public function getResourceName()
    {
        return 'invoice';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.quote_uuid" => 'nullable|exists:quotes,uuid',
                "{$this->getResourceName()}.client_uuid" => 'required|exists:clients,uuid',
                "{$this->getResourceName()}.invoice_number" => 'required',
                "{$this->getResourceName()}.invoice_date" => 'nullable|date',
                "{$this->getResourceName()}.due_date" => 'nullable|date',
                "{$this->getResourceName()}.partial" => 'nullable|numeric',
                "{$this->getResourceName()}.currency_code" => 'nullable|exists:currencies,code',
                "{$this->getResourceName()}.items" => "array",
                "{$this->getResourceName()}.items.*.product_uuid" => 'nullable|exists:products,uuid',

                "{$this->getResourceName()}.applied_credits" => [function ($attributte, $value, $fail) {
                    /**
                     * REFACTOR THISASJKGHFAK
                     *
                     * or move somewhere else
                     */
                    if (!empty($value) && is_array($value)) {
                        foreach ($value as $index => $creditToApply) {
                            if (!$creditToApply['credit_uuid']) {
                                continue;
                            }
                            if ($credit = $this->creditRepository->findActive($creditToApply['credit_uuid'])) {
                                $currencyCode = $creditToApply['currency_code'];

                                $creditBalance = convert_currency(
                                    $credit->balance,
                                    $credit->currency_code,
                                    $currencyCode
                                );

                                $invoiceUuid = request()->route()->parameter('invoice');
                                $invoice = $this->repository->findActive($invoiceUuid);

                                if ($invoice) {
                                    $appliedCredit = $invoice->appliedCredits()
                                        ->where('credit_uuid', $credit->uuid)
                                        ->first();

                                    if ($appliedCredit) {
                                        $creditBalance = bcadd($creditBalance, convert_currency($appliedCredit->amount, $appliedCredit->currency_code, $currencyCode), 2);
                                    }
                                }

                                if (bccomp($creditBalance, $creditToApply['amount'], 2) === -1) {
                                    $fail("applied_credits.{$index}.amount is greater than available credit balance. $creditBalance < {$creditToApply['amount']}");
                                }
                            } else {
                                $fail("applied_credits.{$index}.credit_uuid doesn\'t exist");
                            }
                        }
                    }
                }]
            ],
            static::VALIDATION_RULES_PATCH => [
                "{$this->getResourceName()}.client_uuid" => 'exists:clients,uuid',
                "{$this->getResourceName()}.invoice_date" => 'nullable|date',
                "{$this->getResourceName()}.due_date" => 'nullable|date',
                "{$this->getResourceName()}.partial" => 'nullable|numeric',
                "{$this->getResourceName()}.currency_code" => 'nullable|exists:currencies,code',
                "{$this->getResourceName()}.items" => "array",
                "{$this->getResourceName()}.items.*.product_uuid" => 'nullable|exists:products,uuid'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.client_uuid" => 'client',
            "{$this->getResourceName()}.invoice_date" => 'invoice\'s date',
            "{$this->getResourceName()}.invoice_number" => 'invoice number',
            "{$this->getResourceName()}.due_date" => 'due date',
            "{$this->getResourceName()}.partial" => 'partial'
        ];
    }

    public function preview()
    {
        $invoice = \App\Domain\Model\Documents\Invoice\Invoice::first();

        return view('pdfs.invoice.default.invoice', [
            'noteToClient' => $invoice->notes,
            'poNumber'     => $invoice->po_number,
            'date'         => $invoice->date,
            'items'        => $invoice->items,
            'subTotal'     => $invoice->subTotal(),
            'grandTotal'   => $invoice->amount(),
            'discount'     => $invoice->discount(),
            'tax'          => $invoice->taxes(),
            'footerText'   => $invoice->footer
        ]);
    }
}