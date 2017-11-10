<?php

namespace App\Domain\Model\Documents\Invoice;

use League\Fractal;
use App\Domain\Model\Documents\Payment\Payment;
use App\Domain\Model\Documents\Pdf\PdfTransformer;
use App\Domain\Model\Documents\Bill\BillItemTransformer;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class InvoiceTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'pdfs',
        'items',
        'history'
    ];

    public function transform(Invoice $invoice)
    {
        $amount = $invoice->amount();
        $paid_in = $invoice->paidIn();

        return [
            'uuid' => $invoice->uuid,

            'client_uuid' => $invoice->client_uuid,
            'payments' => $invoice->payments->map(function (Payment $payment) {
                return $payment->uuid;
            }),

            'applied_credits' => $invoice->appliedCredits,

            'amount' => +$amount,
            'paid_in' => +$paid_in,
            'balance' => +($amount - $paid_in),

            'invoice_date' => $invoice->date,
            'due_date' => $invoice->due_date,
            'partial' => +$invoice->partial,
            'currency' => $invoice->currency,
            'invoice_number' => $invoice->invoice_number,
            'po_number' => $invoice->po_number,
            'discount_type' => $invoice->discount_type,
            'discount_value' => +$invoice->discount_value,

            'note_to_client' => $invoice->notes,
            'terms' => $invoice->terms,
            'footer' => $invoice->footer,

            'status' => $invoice->status,

            'is_disabled' => $invoice->is_disabled,

            'created_at' => $invoice->created_at,
            'updated_at' => $invoice->updated_at,
            'deleted_at' => $invoice->deleted_at,
            'archived_at' => $invoice->archived_at
        ];
    }

    public function includePdfs(Invoice $invoice)
    {
        return $this->collection($invoice->pdfs, new PdfTransformer);
    }

    public function includeItems(Invoice $invoice)
    {
        return $this->collection($invoice->items, new BillItemTransformer);
    }

    public function includeHistory(Invoice $invoice)
    {
        return $this->collection($invoice->getHistory(), new ActivityTransformer);
    }
}