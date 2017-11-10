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

    public function getTransformer()
    {
        return new UserTransformer;
    }

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
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    public function vatChecks()
    {
        return $this->hasMany(VatInfo::class);
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
            \App\Domain\Model\Documents\Client\ClientContact::class,
            \App\Domain\Model\Documents\Vendor\VendorContact::class,
            \App\Domain\Model\Documents\Payment\Refund::class,
            \App\Domain\Model\Documents\Profile\Profile::class,
            \App\Domain\Model\Features\VatChecker\VatInfo::class
        ])->orderBy('id', 'desc');
    }

    public function getEmailAttribute()
    {
        return $this->profile->email;
    }

    public function getFullNameAttribute()
    {
        return $this->profile->first_name . ' ' . $this->profile->last_name;
    }

    public function hasPermissionTo($action, $document)
    {
        if ($document instanceof AbstractDocument) {
            return $this->companies()->where('uuid', $document->company_uuid)->exists();
        }
        return true;
    }

    public function scopeWithPermissionTo($query, $action, $document)
    {
        if ($document instanceof AbstractDocument) {
            return $query->whereHas('companies', function ($query) use ($document) {
                return $query->where('uuid', $document->company_uuid);
            });
        }
        return $query;
    }

    public function findForPassport($uuid)
    {
       return $this->find($uuid);
    }
}