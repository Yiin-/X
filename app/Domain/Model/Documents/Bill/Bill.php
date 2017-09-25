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
        'notes',
        'terms',
        'footer',
        'billable_type',
        'billable_uuid'
    ];

    public function billable()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(BillItem::class)->orderBy('index', 'asc');
    }

    public function getTableData()
    {
        return [];
    }
}