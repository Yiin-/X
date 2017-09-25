<?php

namespace App\Domain\Model\Documents\Invoice;

use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;

class InvoiceRepository extends AbstractDocumentRepository
{
    protected $repository;
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = new Repository(Invoice::class);
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

        $invoice = $this->repository->create($data, $protectedData, false);

        $bill = Bill::create([
            'billable_type' => get_class($invoice),
            'billable_uuid' => $invoice->uuid,
            'number' => $data['invoice_number'],
            'po_number' => $data['po_number'],
            'partial' => $data['partial'],
            'discount' => $data['discount_value'],
            'discount_type' => $data['discount_type'],
            'date' => $data['invoice_date'],
            'due_date' => $data['due_date'],
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
                'tax_rate_uuid' => $item['tax_rate_uuid'],
                'index' => $index
            ]);
        }

        $invoice->save();

        return $invoice;
    }

    public function update($data, $protectedData = [])
    {
        $invoice = $this->repository->update($data, $protectedData);

        $billData = [];

        foreach ([
            'invoice_number' => 'number',
            'po_number' => 'po_number',
            'partial' => 'partial',
            'discount_value' => 'discount',
            'discount_type' => 'discount_type',
            'invoice_date' => 'date',
            'due_date' => 'due_date',
            'note_to_client' => 'notes',
            'terms' => 'terms',
            'footer' => 'footer'
        ] as $field => $billField) {
            if (isset($data[$field])) {
                $billData[$billField] = $data[$field];
            }
        }
        $invoice->bill()->update($billData);

        if (isset($data['items'])) {
            $invoice->bill->items()->delete();

            foreach ($data['items'] as $index => $item) {
                $invoice->bill->items()->create([
                    'product_uuid' => $item['product_uuid'],
                    'cost' => $item['cost'],
                    'qty' => $item['qty'],
                    'discount' => $item['discount'],
                    'tax_rate_uuid' => $item['tax_rate_uuid'],
                    'index' => $index
                ]);
            }
        }

        return $invoice;
    }
}