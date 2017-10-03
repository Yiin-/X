<?php

namespace App\Domain\Model\Documents\RecurringExpense;

use App\Domain\Constants\Bill\DiscountTypes;
use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringExpense extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'vendor_uuid',
        'client_uuid',
        // 'category_uuid',

        'amount',
        'currency_id',

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

            'relationships' => [
                'vendor' => $this->vendor_uuid,
                'client' => $this->client_uuid
            ],

            'amount' => $this->amount,
            'currency' => $this->currency,

            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'due_date' => $this->due_date,
            'frequency' => $this->frequency,

            'status' => $this->status,

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

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function expenses()
    {
        return $this->morphMany(Expense::class, 'expensable', null, 'expensable_uuid');
    }
}