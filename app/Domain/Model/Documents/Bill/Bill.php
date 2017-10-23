<?php

namespace App\Domain\Model\Documents\Bill;

use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends AbstractDocument
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'number',
        'po_number',
        'partial',
        'discount',
        'discount_type',
        'date',
        'due_date',
        'currency_code',
        'notes',
        'terms',
        'footer',
        'billable_type',
        'billable_uuid'
    ];

    protected $touches = [
        'billable'
    ];

    protected $dates = [
        'date',
        'due_date'
    ];

    protected $dispatchesEvents = [];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function billable()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(BillItem::class)->orderBy('index', 'asc');
    }

    public function transform()
    {
        return [
            'number' => $this->number,
            'po_number' => $this->po_number,
            'partial' => $this->partial,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'date' => $this->date,
            'due_date' => $this->due_date,
            'currency_code' => $this->currency_code,
            'notes' => $this->notes,
            'terms' => $this->terms,
            'footer' => $this->footer
        ];
    }
}