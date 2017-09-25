<?php

namespace App\Domain\Model\Documents\Payment;

use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Credit\CreditRepository;
use App\Domain\Model\Documents\Invoice\InvoiceRepository;

class PaymentRepository extends AbstractDocumentRepository
{
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

    /**
     * TODO: throw custom exception, if user is not defined
     * @param $data
     * @param array $protectedData
     * @return mixed
     */
    public function create($data, $protectedData = [])
    {
        if (!isset($protectedData['user_uuid'])) {
            $protectedData['user_uuid'] = auth()->id();
        }
        $user = $this->userRepository->find($protectedData['user_uuid']);

        if (!isset($protectedData['company_uuid'])) {
            // TODO: Pick current selected company, not the first one
            $protectedData['company_uuid'] = $user->companies()->first()->uuid;
        }

        $invoice = $this->invoiceRepository->find($data['invoice_uuid']);
        $invoiceBalance = $invoice->balance();

        if ($data['amount'] > $invoiceBalance) {
            $this->creditRepository->create([
                'client_uuid' => $data['client_uuid'],
                'amount' => $data['amount'] - $invoiceBalance,
                'credit_date' => \Carbon\Carbon::now()->toDateString(),
                'credit_number' => 'Credit created by payment ' . $data['payment_reference']
            ]);
            $data['amount'] = $invoiceBalance;
        }

        return $this->repository->create($data, $protectedData);
    }

    public function update($data, $protectedData = [])
    {
        if (isset($data['refunded']) && $data['refunded'] === null) {
            unset($data['refunded']);
        }
        else {
            $document = $this->repository->find($data['uuid']);
            $data['refunded'] += $document->refunded;

            if ($data['refunded'] > $document->amount) {
                $data['refunded'] = $document->amount;
            }
        }

        return $this->repository->update($data, $protectedData);
    }
}