<?php

namespace App\Domain\Model\Documents\RecurringInvoice;

use App\Domain\Model\Documents\Shared\Interfaces\BelongsToClient;
use App\Domain\Constants\Bill\DiscountTypes;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Shared\BillableDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringInvoice extends BillableDocument implements BelongsToClient
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

    public function getTransformer()
    {
        return new RecurringInvoiceTransformer;
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