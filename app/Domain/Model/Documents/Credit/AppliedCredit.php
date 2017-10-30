<?php

namespace App\Domain\Model\Documents\Credit;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Model\Documents\Payment\Payment;

class AppliedCredit extends Model
{
    protected $fillable = [
        'credit_uuid',
        'payment_uuid',
        'amount',
        'currency_code'
    ];

    protected $hidden = [
        'id'
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}