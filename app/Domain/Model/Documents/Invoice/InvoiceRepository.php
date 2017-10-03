<?php

namespace App\Domain\Model\Documents\Invoice;

use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Quote\QuoteRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class InvoiceRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $userRepository;

    public function __construct(UserRepository $userRepository, QuoteRepository $quoteRepository)
    {
        $this->repository = new Repository(Invoice::class);
        $this->userRepository = $userRepository;
        $this->quoteRepository = $quoteRepository;
    }

    public function fillMissingData(&$data, &$protectedData)
    {
        if (isset($data['quote_uuid'])) {
            $protectedData['invoiceable_type'] = Quote::class;
            $protectedData['invoiceable_uuid'] = $data['quote_uuid'];
        }
    }

    public function saving($invoice, &$data, &$protectedData)
    {
        $bill = Bill::create([
            'billable_type' => get_class($invoice),
            'billable_uuid' => $invoice->uuid,
            'number' => $data['invoice_number'],
            'po_number' => $data['po_number'],
            'partial' => $data['partial'],
            'discount' => $data['discount_value'],
            'discount_type' => $data['discount_type'],
            'currency_id' => $data['currency_id'],
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
    }

    public function updated(&$invoice, &$data, &$protectedData)
    {
        $billData = [];

        foreach ([
            'invoice_number' => 'number',
            'po_number' => 'po_number',
            'partial' => 'partial',
            'discount_value' => 'discount',
            'discount_type' => 'discount_type',
            'invoice_date' => 'date',
            'due_date' => 'due_date',
            'currency_id' => 'currency_id',
            'note_to_client' => 'notes',
            'terms' => 'terms',
            'footer' => 'footer'
        ] as $field => $billField) {
            if (array_key_exists($field, $data)) {
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
    }
}