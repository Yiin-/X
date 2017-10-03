<?php

namespace App\Domain\Model\Authentication\User;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'key',
        'value'
    ];
}