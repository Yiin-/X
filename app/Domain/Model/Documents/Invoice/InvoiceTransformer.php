<?php

namespace App\Domain\Model\Documents\Invoice;

use League\Fractal;
use Money\Currency;
use Money\Money;

class InvoiceTransformer extends Fractal\TransformerAbstract
{
    public function transform(Invoice $invoice)
    {
        $amount = $invoice->amount();
        $paid_in = $invoice->paidIn();

        return [
            'uuid' => $invoice->uuid,

            'relationships' => [
                'client' => $invoice->client_uuid,
                'payments' => $invoice->payments->map(function (Payment $payment) {
                    return $payment->uuid;
                }),
            ],

            'amount' => (float)$amount,
            'paid_in' => (float)$paid_in,
            'balance' => (float)$amount - (float)$paid_in,

            'invoice_date' => $invoice->bill->date,
            'due_date' => $invoice->bill->due_date,
            'partial' => $invoice->bill->partial,
            'invoice_number' => $invoice->bill->number,
            'po_number' => $invoice->bill->po_number,
            'discount_type' => $invoice->bill->discount_type,
            'discount_value' => $invoice->bill->discount,
            'items' => $invoice->bill->items->map(function (BillItem $item) {
                return $item->transform();
            }),
            'note_to_client' => $invoice->bill->notes,
            'terms' => $invoice->bill->terms,
            'footer' => $invoice->bill->footer,

            'status' => $invoice->status,

            'created_at' => $invoice->created_at,
            'updated_at' => $invoice->updated_at,
            'deleted_at' => $invoice->deleted_at,
            'archived_at' => $invoice->archived_at
        ];
        return [
            'id'      => (int) $book->id,
            'title'   => $book->title,
            'year'    => (int) $book->yr,
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => '/books/'.$book->id,
                ]
            ],
        ];
    }
}