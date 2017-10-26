<?php

namespace App\Domain\Model\Documents\Passive;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'precision',
        'iso_3166_2'
    ];

    public function rates()
    {
        return $this->hasMany(CurrencyRate::class, 'base', 'code');
    }
}