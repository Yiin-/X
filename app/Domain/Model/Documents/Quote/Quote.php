<?php

namespace App\Domain\Model\Documents\Quote;

use App\Domain\Model\Documents\Shared\Interfaces\BelongsToClient;
use App\Domain\Constants\Bill\DiscountTypes;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\Shared\BillableDocument;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\Model\Documents\Pdf\Pdf;

class Quote extends BillableDocument implements BelongsToClient
{
    use SoftDeletes;

    protected $fillable = [
        'client_uuid',
        'status',
        'quote_number',
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
    public function paidIn()
    {
        return round(bcadd($this->partial, $this->applied_credits_sum, 2), 2);
    }

    public function getTransformer()
    {
        return new QuoteTransformer;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoiceable', null, 'invoiceable_uuid');
    }
}