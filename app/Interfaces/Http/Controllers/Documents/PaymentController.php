<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Payment\PaymentRepository;
use App\Domain\Model\Documents\Credit\CreditRepository;
use App\Domain\Model\Documents\Credit\AppliedCredit;

class PaymentController extends DocumentController
{
    protected $repository;

    public function __construct(
        PaymentRepository $paymentRepository,
        CreditRepository $creditRepository
    ) {
        $this->repository = $paymentRepository;
        $this->creditRepository = $creditRepository;
    }

    public function getResourceName()
    {
        return 'payment';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'required|exists:clients,uuid',
                "{$this->getResourceName()}.invoice_uuid" => 'required|exists:invoices,uuid',
                "{$this->getResourceName()}.payment_reference" => 'required',
                "{$this->getResourceName()}.amount" => 'required',
                "{$this->getResourceName()}.currency_code" => 'required|exists:currencies,code',
                "{$this->getResourceName()}.payment_type_id" => 'nullable|exists:payment_types,id',
                "{$this->getResourceName()}.payment_date" => 'nullable|date',
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
                                $currencyCode = request()->get($this->getResourceName())['currency_code'];

                                $creditBalance = convert_currency(
                                    $credit->balance,
                                    $credit->currency_code,
                                    $currencyCode
                                );

                                $appliedCredit = AppliedCredit::where([
                                    'credit_uuid' => $credit->uuid,
                                    'payment_uuid' => request()->route()->parameter('payment')
                                ])->first();

                                if ($appliedCredit) {
                                    $creditBalance += convert_currency($appliedCredit->amount, $appliedCredit->currency_code, $currencyCode);
                                }

                                if ($creditBalance < $creditToApply['amount']) {
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
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.client_uuid" => 'exists:clients,uuid',
                "{$this->getResourceName()}.invoice_uuid" => 'exists:invoices,uuid',
                "{$this->getResourceName()}.currency_code" => 'exists:currencies,code',
                "{$this->getResourceName()}.payment_type_id" => 'nullable|exists:payment_types,id',
                "{$this->getResourceName()}.payment_date" => 'nullable|date'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.client_uuid" => 'client',
            "{$this->getResourceName()}.invoice_uuid" => 'invoice',
            "{$this->getResourceName()}.amount" => 'payment\'s amount',
            "{$this->getResourceName()}.payment_reference" => 'payment reference',
            "{$this->getResourceName()}.currency_code" => 'currency',
            "{$this->getResourceName()}.payment_type_id" => 'payment\'s type',
            "{$this->getResourceName()}.payment_date" => 'payment\'s date'
        ];
    }
}