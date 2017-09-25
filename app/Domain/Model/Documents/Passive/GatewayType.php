<?php

namespace App\Domain\Model\Documents\Passive;

use Illuminate\Database\Eloquent\Model;

class GatewayType extends Model
{
    protected $fillable = [
        'name',
        'alias'
    ];
}