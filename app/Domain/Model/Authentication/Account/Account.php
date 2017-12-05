<?php

namespace App\Domain\Model\Authentication\Account;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Events\Authentication\AccountWasCreated;
use App\Domain\Events\Authentication\AccountWasUpdated;

class Account extends AbstractDocument
{
    protected $fillable = [
        'name',
        'site_address'
    ];

    protected $dispatchesEvents = [];

    protected $documentEvents = [
        'created' => AccountWasCreated::class,
        'updated' => AccountWasUpdated::class
    ];

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