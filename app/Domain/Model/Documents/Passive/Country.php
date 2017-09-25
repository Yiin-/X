<?php

namespace App\Domain\Model\Documents\Passive;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'capital',
        'citizenship',
        'country_code',
        'currency',
        'currency_code',
        'currency_sub_unit',
        'full_name',
        'iso_3166_2',
        'iso_3166_3',
        'name',
        'region_code',
        'sub_region_code',
        'eea'
    ];
}