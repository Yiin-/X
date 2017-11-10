<?php

namespace App\Domain\Model\Documents\Credit;

use Illuminate\Database\Eloquent\Model;

class AppliedCredit extends Model
{
    protected $fillable = [
        'credit_uuid',
        'billable_type',
        'billable_id',
        'amount',
        'currency_code'
    ];

    protected $hidden = [
        'id',
        'billable_type',
        'billable_id'
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function billable()
    {
        return $this->morphTo();
    }

    public function document()
    {
        return $this->billable;
    }
}