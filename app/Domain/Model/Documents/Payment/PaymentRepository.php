<?php

namespace App\Domain\Model\Documents\Payment;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Credit\CreditRepository;
use App\Domain\Model\Documents\Invoice\InvoiceRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

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
        if (isset($data['invoice_uuid']) && isset($data['client_uuid']) && isset($data['amount'])) {
            $invoice = $this->invoiceRepository->find($data['invoice_uuid']);
            $invoiceBalance = $invoice->balance();

            if ($data['amount'] > $invoiceBalance) {
                $this->creditRepository->create([
                    'client_uuid' => $data['client_uuid'],
                    'amount' => $data['amount'] - $invoiceBalance,
                    'currency_code' => $data['currency_code'],
                    'credit_date' => \Carbon\Carbon::now()->toDateString(),
                    'credit_number' => 'Credit created by payment ' . ($data['payment_reference'] ?? '<payment has no reference>')
                ]);
                $data['amount'] = $invoiceBalance;
            }
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
}