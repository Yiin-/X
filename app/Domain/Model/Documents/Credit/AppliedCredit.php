<?php

namespace App\Domain\Model\Documents\Credit;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Model\Documents\Bill\Bill;

class AppliedCredit extends Model
{
    protected $fillable = [
        'credit_uuid',
        'bill_id',
        'amount',
        'currency_code'
    ];

    protected $hidden = [
        'id',
        'bill_id'
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function document()
    {
        return $this->bill->billable;
    }
}