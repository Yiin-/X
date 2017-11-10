<?php

namespace App\Domain\Model\Authentication\Account;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authentication\User\User;

class Account extends AbstractDocument
{
    protected $fillable = [
        'name',
        'site_address'
    ];

    protected $dispatchesEvents = [];

    public function getTransformer()
    {
        return new AccountTransformer;
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}