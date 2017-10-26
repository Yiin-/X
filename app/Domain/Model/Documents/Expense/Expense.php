<?php

namespace App\Domain\Model\Documents\Expense;

use App\Domain\Model\Documents\Vendor\Vendor;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'vendor_uuid',
        'client_uuid',
        'amount',
        'currency_code',
        'date'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'archived_at',
        'deleted_at'
    ];

    public function transform()
    {
        return [
            'uuid' => $this->uuid,

            'vendor' => [ 'uuid' => $this->vendor_uuid ],
            'client' => [ 'uuid' => $this->client_uuid ],
            'invoice' => [ 'uuid' => $this->invoice ? $this->invoice->uuid : null ],

            'amount' => +$this->amount,
            'currency' => $this->currency,
            'date' => $this->date,

            'is_disabled' => $this->is_disabled,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'archived_at' => $this->archived_at,
            'deleted_at' => $this->deleted_at
        ];
    }

    /**
     * Used to track automatically generated recurring expenses
     */
    public function expensable()
    {
        return $this->morphTo();
    }

    /**
     * Vendor that we bought items from.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Relation to invoiced expense
     */
    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoiceable', null, 'invoiceable_uuid');
    }

    /**
     * Client that we're invoicing for this expense.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Currency of the expense.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}