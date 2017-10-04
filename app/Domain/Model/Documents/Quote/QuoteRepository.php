<?php

namespace App\Domain\Model\Documents\Quote;

use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Shared\AbstractDocumentRepository;
use App\Infrastructure\Persistence\Repository;
use App\Domain\Model\Authentication\User\UserRepository;
use App\Domain\Model\Documents\Shared\Traits\FillsUserData;

class QuoteRepository extends AbstractDocumentRepository
{
    use FillsUserData;

    protected $repository;
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = new Repository(Quote::class);
        $this->userRepository = $userRepository;
    }

    public function savingNew(&$quote, &$data, &$protectedData)
    {
        $bill = Bill::create([
            'billable_type' => get_class($quote),
            'billable_uuid' => $quote->uuid,
            'number' => $data['quote_number'],
            'po_number' => $data['po_number'],
            'partial' => $data['partial'],
            'discount' => $data['discount_value'],
            'discount_type' => $data['discount_type'],
            'date' => $data['quote_date'],
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
                'tax_rate_uuid' => $item['tax_rate_uuid'] ?? null,
                'index' => $index
            ]);
        }
    }

    public function updated(&$quote, &$data, &$protectedData)
    {
        $quote->bill->update([
            'number' => $data['quote_number'],
            'po_number' => $data['po_number'],
            'partial' => $data['partial'],
            'discount' => $data['discount_value'],
            'discount_type' => $data['discount_type'],
            'date' => $data['quote_date'],
            'due_date' => $data['due_date'],
            'notes' => $data['note_to_client'],
            'terms' => $data['terms'],
            'footer' => $data['footer']
        ]);

        $quote->bill->items()->delete();

        foreach ($data['items'] as $index => $item) {
            $quote->bill->items()->create([
                'product_uuid' => $item['product_uuid'],
                'cost' => $item['cost'],
                'qty' => $item['qty'],
                'discount' => $item['discount'],
                'tax_rate_uuid' => $item['tax_rate_uuid'],
                'index' => $index
            ]);
        }

        return $quote;
    }
}