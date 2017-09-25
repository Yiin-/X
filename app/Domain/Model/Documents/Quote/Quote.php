<?php

namespace App\Domain\Model\Documents\Quote;

use App\Domain\Constants\Bill\DiscountTypes;
use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'client_uuid'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function calcAmount()
    {
        $amount = 0;

        foreach ($this->bill->items as $item) {
            $amount += $item->final_price;
        }

        switch ($this->bill->discount_type) {
            case DiscountTypes::FLAT:
                $amount -= $this->bill->discount;
                break;
            case DiscountTypes::PERCENTAGE:
                $amount -= $amount * ($this->bill->discount / 100);
                break;
        }

        return $amount;
    }

    public function getTableData()
    {
        return [
            'uuid' => $this->uuid,

            'relationships' => [
                'client' => $this->client_uuid,
                'invoice' => $this->invoice ? $this->invoice->uuid : null
            ],

            'amount' => $this->calcAmount(),

            'quote_date' => $this->bill->date,
            'due_date' => $this->bill->due_date,
            'partial' => $this->bill->partial,
            'quote_number' => $this->bill->number,
            'po_number' => $this->bill->po_number,
            'discount_type' => $this->bill->discount_type,
            'discount_value' => $this->bill->discount,
            'items' => $this->bill->items->map(function (BillItem $item) {
                return $item->getTableData();
            }),
            'note_to_client' => $this->bill->notes,
            'terms' => $this->bill->terms,
            'footer' => $this->bill->footer,

            'status' => $this->status,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'archived_at' => $this->archived_at,
            'deleted_at' => $this->deleted_at
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function bill()
    {
        return $this->morphOne(Bill::class, 'billable', null, 'billable_uuid');
    }

    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoiceable', null, 'invoiceable_uuid');
    }
}