<?php

namespace App\Domain\Model\Authentication\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Passport\HasApiTokens;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\Account\Account;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Model\Authorization\Permission\Permission;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Profile\Profile;
use App\Domain\Model\Features\VatChecker\VatCheck;

class User extends AbstractDocument implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use HasApiTokens, Authenticatable, Authorizable, CanResetPassword;

    protected $fillable = [
        'username'
    ];

    protected $hidden = [
        'account_uuid',
        'password',
        'remember_token'
    ];

    public function getTableData()
    {
        return [
            'uuid' => $this->uuid,
            'full_name' => $this->full_name
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'user_company');
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function permissions()
    {
        return $this->belongsToManyLeftJoin(Permission::class, 'user_permission')
                    ->belongsToManyThrough($this, Role::class, 'user_role', 'role_permission');
    }

    public function vatChecks()
    {
        return $this->hasMany(VatCheck::class);
    }

    public function getFullNameAttribute()
    {
        return $this->profile->first_name . ' ' . $this->profile->last_name;
    }

    public function hasPermissionTo($action, $document)
    {
        if ($document instanceof AbstractDocument) {
            return $this->permissions()
                ->where('type', $action)
                ->where('permissible_type', get_class($document))
                ->where('permissible_uuid', $document->uuid)
                ->exists();
        }
        return $this->permissions()
            ->wherE('type', $action)
            ->where('permissible_type', $document)
            ->exists();
    }

    public function scopeWithPermissionTo($query, $action, $document)
    {
        if ($document instanceof AbstractDocument) {
            return $query->whereHas('permissions', function ($query) use ($action, $document) {
                $query->where('type', $action)
                    ->where('permissible_type', get_class($document))
                    ->where('permissible_uuid', $document->uuid);
            });
        }
        return $query->whereHas('permissions', function ($query) use ($action, $document) {
            $query->where('type', $action)
                ->where('permissible_type', $document);
        });
    }

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }
}