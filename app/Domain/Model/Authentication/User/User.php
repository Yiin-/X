<?php

namespace App\Domain\Model\Authentication\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Passport\HasApiTokens;

use App\Domain\Constants\Permission\Scopes as PermissionScope;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\Account\Account;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Model\Authorization\Permission\Permission;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Employee\Employee;
use App\Domain\Model\Features\VatChecker\VatInfo;
use App\Domain\Model\System\ActivityLog\Activity;

class User extends AbstractDocument implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use HasApiTokens, Authenticatable, Authorizable, CanResetPassword;

    protected $fillable = [
        'username',
        'taskbar'
    ];

    protected $hidden = [
        'pin_code',
        'account_uuid',
        'password',
        'remember_token',
        'confirmation_token'
    ];

    protected $appends = [
        'email',
        'full_name'
    ];

    protected $dispatchesEvents = [];
    protected $documentEvents = [];

    public function getTransformer()
    {
        return new UserTransformer;
    }

    public function authenticable()
    {
        return $this->morphTo()->withTrashed();
    }

    public function vatChecks()
    {
        return $this->hasMany(VatInfo::class);
    }

    public function role()
    {
        return $this->morphOne(Role::class, 'roleable');
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

    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }

    public function preferences()
    {
        return $this->hasMany(UserPreference::class);
    }

    public function getPreferencesAttribute()
    {
        return $this->preferences()->get()->mapWithKeys(function (UserPreference $preference) {
            return [ $preference->key => $preference->value ];
        });
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->whereNotIn('document_type', [
            \App\Domain\Model\Documents\Bill\BillItem::class,
            \App\Domain\Model\Documents\Contact\Contact::class,
            \App\Domain\Model\Documents\Payment\Refund::class,
            \App\Domain\Model\Features\VatChecker\VatInfo::class
        ])->orderBy('id', 'desc');
    }

    public function getEmailAttribute()
    {
        if ($this->employee) {
            return $this->employee->email;
        }
        return '';
    }

    public function getFullNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->first_name . ' ' . $this->employee->last_name;
        }
        return '';
    }

    public function hasSameOrBetterRole(Role $role)
    {
        $list = [];

        do {
            $list[] = $role->uuid;
            $role = $role->parent;
        }
        while($role);

        return $this->roles()->whereIn('uuid', $list)->exists();
    }

    /**
     * Check if user instance has permission to do something
     * with given document or it's type
     *
     * @param  integer                  $action    Action id from \App\Domain\Constants\Permission\Actions
     * @param  AbstractDocument|string  $document  Document instance or it's class
     * @return boolean
     */
    public function hasPermissionTo($action, $document, $scope = null)
    {
        if (is_string($document) && $scope === null) {
            $scope = auth()->user()->companies()->first();
        }
        return $this->roles()->whereHas('permissions', function ($query) use ($action, $document, $scope) {
            return $query->can($action, $document, $scope);
        })->exists();
    }

    /**
     * Filter out users without permission to do something
     * with given document or it's type
     *
     * @param  mixed                    $query     QueryBuilder object
     * @param  integer                  $action    Action id from \App\Domain\Constants\Permission\Actions
     * @param  AbstractDocument|string  $document  Document instance or it's class
     * @return boolean
     */
    public function scopeWithPermissionTo($query, $action, $document, $scope = null)
    {
        return $query->whereHas('roles', function ($query) use ($action, $document, $scope) {
            return $query->withPermissionTo($action, $document, $scope);
        });
    }

    public function findForPassport($uuid)
    {
       return $this->find($uuid);
    }

    /**
     * Not used atm
     */
    public function invoice_number_pattern()
    {
        return $this->preferences()->where('key', 'invoice_number_pattern')->first()->value;
    }

    public function recurring_invoice_number_pattern()
    {
        return $this->preferences()->where('key', 'recurring_invoice_number_pattern')->first()->value;
    }

    public function quote_number_pattern()
    {
        return $this->preferences()->where('key', 'quote_number_pattern')->first()->value;
    }
}