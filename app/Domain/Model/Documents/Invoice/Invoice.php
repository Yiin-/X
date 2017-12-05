<?php

namespace App\Domain\Model\Documents\Invoice;

use App\Domain\Model\Documents\Shared\Interfaces\BelongsToClient;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Payment\Payment;
use App\Domain\Model\Documents\Pdf\Pdf;
use App\Domain\Model\Documents\Shared\BillableDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends BillableDocument implements BelongsToClient
{
    use SoftDeletes;

    protected $fillable = [
        'client_uuid',
        'status',
        'invoice_number',
        'po_number',
        'partial',
        'discount_value',
        'discount_type',
        'date',
        'due_date',
        'currency_code',
        'notes',
        'terms',
        'footer'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    protected $dates = [
        'date',
        'due_date',
        'archived_at'
    ];

    public function loadRelationships()
    {
        $this->load(['items', 'appliedCredits']);
    }

    /**
     * Paid in amount
     */
    public function paidIn($options = [])
    {
        return round(
            bcadd(
                bcadd(
                    (
                        isset($options['exclude_payments']) && $options['exclude_payments']
                        ? 0
                        : $this->payments()->sum('amount')
                    ), $this->partial, 2
                ), $this->applied_credits_sum, 2
            ), 2
        );
    }

    /**
     * Left to pay amount
     */
    public function balance($options = [])
    {
        return bcsub($this->amount(), $this->paidIn($options));
    }

    public function getTransformer()
    {
        return new InvoiceTransformer;
    }

    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function client()
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}