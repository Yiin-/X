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

    public function transform()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'logo_url' => $this->logo_url
        ];
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}