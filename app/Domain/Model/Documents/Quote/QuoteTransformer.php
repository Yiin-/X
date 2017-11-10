<?php

namespace App\Domain\Model\Documents\Quote;

use League\Fractal;
use App\Domain\Model\Documents\Pdf\PdfTransformer;
use App\Domain\Model\Documents\Bill\BillItemTransformer;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class QuoteTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'pdfs',
        'items',
        'history'
    ];

    public function transform(Quote $quote)
    {
        return [
            'uuid' => $quote->uuid,

            'client_uuid' => $quote->client_uuid,
            'invoice_uuid' => $quote->invoice ? $quote->invoice->uuid : null,

            'applied_credits' => $quote->appliedCredits,

            'amount' => +$quote->amount(),

            'quote_date' => $quote->date,
            'due_date' => $quote->due_date,
            'partial' => +$quote->partial,
            'currency' => $quote->currency,
            'quote_number' => $quote->quote_number,
            'po_number' => $quote->po_number,
            'discount_type' => $quote->discount_type,
            'discount_value' => +$quote->discount_value,
            'note_to_client' => $quote->notes,
            'terms' => $quote->terms,
            'footer' => $quote->footer,

            'status' => $quote->status,

            'is_disabled' => $quote->is_disabled,

            'created_at' => $quote->created_at,
            'updated_at' => $quote->updated_at,
            'archived_at' => $quote->archived_at,
            'deleted_at' => $quote->deleted_at
        ];
    }

    public function includePdfs(Quote $quote)
    {
        return $this->collection($quote->pdfs, new PdfTransformer);
    }

    public function includeItems(Quote $quote)
    {
        return $this->collection($quote->items, new BillItemTransformer);
    }

    public function includeHistory(Quote $quote)
    {
        return $this->collection($quote->getHistory(), new ActivityTransformer);
    }
}