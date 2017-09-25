<?php

namespace App\Domain\Model\Documents\Passive;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'locale'
    ];
}