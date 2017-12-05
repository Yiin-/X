<?php

namespace App\Domain\Model\Authentication\Company;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Authentication\Account\Account;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authorization\Role\Role;

class Company extends AbstractDocument
{
    protected $fillable = [
        'name',
        'email',
        'logo_url'
    ];

    protected $hidden = [
        'account_uuid'
    ];

    protected $dispatchesEvents = [];

    public function getTransformer()
    {
        return new CompanyTransformer;
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_company');
    }

    public function roles()
    {
        return $this->morphMany(Role::class, 'roleable');
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}