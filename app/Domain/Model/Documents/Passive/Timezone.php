<?php

namespace App\Domain\Model\Documents\Passive;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $fillable = [
        'name',
        'location'
    ];
}