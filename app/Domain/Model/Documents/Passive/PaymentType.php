<?php

namespace App\Domain\Model\Documents\Passive;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    protected $fillable = [
        'name',
        'gateway_type_id'
    ];
}