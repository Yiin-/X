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

    public function creating(&$data)
    {
        $invoice = $this->invoiceRepository->findActive($data['invoice_uuid']);
        $invoiceBalance = $invoice->balance();

        $invoiceCurrencyCode = $invoice->bill->currency_code;
        $amount = convert_currency($data['amount'], $data['currency_code'], $invoiceCurrencyCode);

        $data['currency_code'] = $invoiceCurrencyCode;
        $data['amount'] = $amount;

        if ($amount > $invoiceBalance) {
            $credit = $this->creditRepository->create([
                'client_uuid' => $data['client_uuid'],
                'balance' => $amount - $invoiceBalance,
                'currency_code' => $invoiceCurrencyCode,
                'credit_date' => \Carbon\Carbon::now()->toDateString(),
                'credit_number' => 'Credit created by payment ' . ($data['payment_reference'] ?? '<payment has no reference>')
            ]);

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
        }
    }
}