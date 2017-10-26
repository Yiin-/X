<?php

namespace App\Domain\Model\Authentication\User;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Passive\Language;

class UserSettings extends Model
{
    protected $fillable = [
        'currency_code',
        'locale'
    ];

    protected $with = [
        'currency'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}