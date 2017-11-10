<?php

namespace App\Domain\Model\Documents\RecurringInvoice;

use App\Domain\Constants\Bill\DiscountTypes;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Shared\BillableDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringInvoice extends BillableDocument
{
    use SoftDeletes;

    protected $fillable = [
        'client_uuid',
        'start_date',
        'end_date',
        'due_date',
        'frequency_value',
        'frequency_type',
        'autobill',
        'status'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'last_sent_at',
        'created_at',
        'updated_at',
        'archived_at',
        'deleted_at'
    ];

    public function transform()
    {
        return [
            'uuid' => $this->uuid,

            'client' => [ 'uuid' => $this->client_uuid ],

            'amount' => +$this->amount(),

            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'due_date' => $this->due_date,
            'frequency' => $this->frequency,
            'frequency_type' => $this->frequency_type,
            'frequency_value' => $this->frequency_value,

            'po_number' => $this->bill->po_number,
            'discount_type' => $this->bill->discount_type,
            'discount_value' => $this->bill->discount_value,
            'items' => $this->bill->items->map(function (BillItem $item) {
                return $item->transform();
            }),
            'note_to_client' => $this->bill->notes,
            'terms' => $this->bill->terms,
            'footer' => $this->bill->footer,

            'status' => $this->status,

            'is_disabled' => $this->is_disabled,

            'last_sent_at' => $this->created_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'archived_at' => $this->archived_at,
            'deleted_at' => $this->deleted_at
        ];
    }

    public function getFrequencyAttribute()
    {
        if ($this->frequency_value !== null && $this->frequency_type !== null) {
            return $this->frequency_value . ':' . $this->frequency_type;
        }
        return '';
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}