<?php

namespace App\Domain\Model\Documents\RecurringInvoice;

use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;

class RecurringInvoiceRepository extends AbstractDocumentRepository
{
    protected $repository;
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = new Repository(RecurringInvoice::class);
        $this->userRepository = $userRepository;
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

        $recurringInvoice = $this->repository->create($data, $protectedData, false);

        $bill = Bill::create([
            'billable_type' => get_class($recurringInvoice),
            'billable_uuid' => $recurringInvoice->uuid,
            'po_number' => $data['po_number'],
            'discount' => $data['discount_value'],
            'discount_type' => $data['discount_type'],
            'date' => $data['start_date'],
            'notes' => $data['note_to_client'],
            'terms' => $data['terms'],
            'footer' => $data['footer']
        ]);

        foreach ($data['items'] as $index => $item) {
            $bill->items()->create([
                'product_uuid' => $item['product_uuid'],
                'cost' => $item['cost'],
                'qty' => $item['qty'],
                'discount' => $item['discount'],
                'tax_rate_uuid' => $item['tax_rate_uuid'] ?? null,
                'index' => $index
            ]);
        }

        $recurringInvoice->save();

        return $recurringInvoice;
    }

    public function update($data, $protectedData = [])
    {
        $recurringInvoice = $this->repository->update($data, $protectedData);

        $billData = [];

        foreach ([
            'po_number' => 'po_number',
            'discount_value' => 'discount',
            'discount_type' => 'discount_type',
            'start_date' => 'date',
            'note_to_client' => 'notes',
            'terms' => 'terms',
            'footer' => 'footer'
        ] as $field => $billField) {
            if (isset($data[$field])) {
                $billData[$billField] = $data[$field];
            }
        }
        $recurringInvoice->bill->update($billData);

        if (isset($data['items'])) {
            $recurringInvoice->bill->items()->delete();

            foreach ($data['items'] as $index => $item) {
                $recurringInvoice->bill->items()->create([
                    'product_uuid' => $item['product_uuid'],
                    'cost' => $item['cost'],
                    'qty' => $item['qty'],
                    'discount' => $item['discount'],
                    'tax_rate_uuid' => $item['tax_rate_uuid'],
                    'index' => $index
                ]);
            }
        }

        return $recurringInvoice;
    }
}