<?php

namespace App\Domain\Model\Documents\Payment;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Credit\CreditRepository;
use App\Domain\Model\Documents\Credit\AppliedCredit;
use App\Domain\Model\Documents\Invoice\InvoiceRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;
use Illuminate\Validation\ValidationException;

class PaymentRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $userRepository;
    protected $creditRepository;
    protected $invoiceRepository;

    public function __construct(
        UserRepository $userRepository,
        CreditRepository $creditRepository,
        InvoiceRepository $invoiceRepository
    ) {
        $this->repository = new Repository(Payment::class);
        $this->userRepository = $userRepository;
        $this->creditRepository = $creditRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function fillMissingData(&$data, &$protectedData)
    {
        if (empty($data['payment_date'])) {
            $data['payment_date'] = date('Y-m-d');
        }
    }

    public function creating(&$data)
    {
        // if ($totalAmount > $invoiceBalance) {
            // $credit = $this->creditRepository->create([
            //     'client_uuid' => $data['client_uuid'],
            //     'amount' => $data['amount'] - $invoiceBalance,
            //     'currency_code' => $data['currency_code'],
            //     'credit_date' => \Carbon\Carbon::now()->toDateString(),
            //     'credit_number' => 'Credit created by payment ' . ($data['payment_reference'] ?? '<payment has no reference>')
            // ]);

            /**
             * Uncomment if we want to decrease payment ammount to match
             * invoice balance. E.g. if invoice was missing payment
             * for $10 and we created a payment for $50, payment amount
             * would be changed to $10, and credit of $40 for client would be
             * saved.
             *
             * Atm, both credit of $40 is saved, and payment amount is saved as $50
             */
            // $data['amount'] = $invoiceBalance;
        // }
    }

    public function savingNew(&$document, &$data)
    {
        $invoice = $this->invoiceRepository->find($data['invoice_uuid']);
        $invoiceBalance = $invoice->balance();

        $appliedCredits = [];

        /**
         * Apply Credits
         */
        if ($data['applied_credits']) {
            /**
             * Go though each of the applied credit
             */
            foreach ($data['applied_credits'] as $creditToApply) {
                if (!$creditToApply['credit_uuid']) {
                    continue;
                }
                $credit = $this->creditRepository->find($creditToApply['credit_uuid']);
                $currencyCode = $data['currency_code'];

                $amountToApplyInPaymentCurrency = $creditToApply['amount'];
                $amountToApplyInCreditCurrency = convert_currency($amountToApplyInPaymentCurrency, $currencyCode, $credit->currency_code);

                $credit->balance -= $amountToApplyInCreditCurrency;
                $credit->save();

                AppliedCredit::create([
                    'credit_uuid' => $credit->uuid,
                    'payment_uuid' => $document->uuid,
                    'amount' => $amountToApplyInCreditCurrency,
                    'currency_code' => $credit->currency_code
                ]);
            }
        }
    }

    public function updated(&$document, &$data)
    {
        $currencyCode = $data['currency_code'];

        foreach ($data['applied_credits'] as $newAppliedCredit) {
            if (!$newAppliedCredit['credit_uuid']) {
                continue;
            }
            if ($appliedCredit = $document->appliedCredits()->where('credit_uuid', $newAppliedCredit['credit_uuid'])->first()) {
                /**
                 * Adjust credit
                 */
                $differenceInPaymentCurrency = $newAppliedCredit['amount'] - convert_currency($appliedCredit->amount, $appliedCredit->currency_code, $currencyCode);
                $differenceInCreditCurency = convert_currency($differenceInPaymentCurrency, $currencyCode, $appliedCredit->credit->currency_code);
                $appliedCredit->credit->balance -= $differenceInCreditCurency;
                $appliedCredit->credit->save();
                $appliedCredit->amount = $newAppliedCredit['amount'];
                $appliedCredit->currency_code = $currencyCode;
                $appliedCredit->save();
            } else {
                /**
                 * Create new applied credit
                 */
                $credit = $this->creditRepository->find($newAppliedCredit['credit_uuid']);

                $amountToApplyInPaymentCurrency = $newAppliedCredit['amount'];
                $amountToApplyInCreditCurrency = convert_currency($amountToApplyInPaymentCurrency, $currencyCode, $credit->currency_code);

                $credit->balance -= $amountToApplyInCreditCurrency;
                $credit->save();

                AppliedCredit::create([
                    'credit_uuid' => $credit->uuid,
                    'payment_uuid' => $document->uuid,
                    'amount' => $amountToApplyInCreditCurrency,
                    'currency_code' => $credit->currency_code
                ]);
            }
        }
        $document->load(['appliedCredits']);
    }

    public function adjustData(&$data)
    {
        if (isset($data['refunded'])) {
            if ($data['refunded'] === null) {
                unset($data['refunded']);
            }
            else {
                $document = $this->repository->find($data['uuid']);
                $data['refunded'] += $document->refunded;

                if ($data['refunded'] > $document->amount) {
                    $data['refunded'] = $document->amount;
                }
            }
        }
    }
}