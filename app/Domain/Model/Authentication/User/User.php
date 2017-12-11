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
use App\Domain\Model\Documents\Passive\Country;
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

    /**
     * Public profile (i.e. Employee or Client),
     * that this user account is assigned to.
     */
    public function authenticable()
    {
        return $this->morphTo()->withTrashed();
    }

    /**
     * VAT checks this user has performed.
     */
    public function vatChecks()
    {
        return $this->hasMany(VatInfo::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }

    /**
     * Private role that's used for setting permissions
     * directly for this user only.
     */
    public function role()
    {
        return $this->morphOne(Role::class, 'roleable');
    }

    /**
     * All roles that are assigned to user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /**
     * Account under which this user is created.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Assigned companies
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'user_company');
    }

    /**
     * Assigned countries
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'user_country');
    }

    /**
     * Assigned clients
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'user_client');
    }

    /**
     * List of user settings
     */
    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }

    /**
     * List of saved user preferences
     */
    public function preferences()
    {
        return $this->hasMany(UserPreference::class);
    }

    /**
     * Convert [[key, value], ...] array of preferences to
     * [key => value, ...] mapped array.
     */
    public function getPreferencesAttribute()
    {
        return $this->preferences()->get()->mapWithKeys(function (UserPreference $preference) {
            return [ $preference->key => $preference->value ];
        });
    }

    /**
     * Activity of the user.
     *
     * Gets list of things he did.
     */
    public function activity()
    {
        return $this->hasMany(Activity::class)->whereNotIn('document_type', [
            \App\Domain\Model\Documents\Bill\BillItem::class,
            \App\Domain\Model\Documents\Contact\Contact::class,
            \App\Domain\Model\Documents\Payment\Refund::class,
            \App\Domain\Model\Features\VatChecker\VatInfo::class
        ])->orderBy('id', 'desc');
    }

    /**
     * Ease the access to email address of the user.
     */
    public function getEmailAttribute()
    {
        if ($this->authenticable) {
            return $this->authenticable->email;
        }
        return $this->username;
    }

    /**
     * Format full name of the user.
     */
    public function getFullNameAttribute()
    {
        if ($this->authenticable) {
            if ($this->authenticable instanceof Employee) {
                return $this->authenticable->full_name;
            }
            if ($this->authenticable instanceof Client) {
                return $this->authenticable->name;
            }
        }
        return '';
    }

    /**
     * Generate token that is used to identify
     * user that is accepting the invitation.
     */
    public function genInvitationToken()
    {
        $invitationToken = str_random();

        $this->invitation_token = $invitationToken;
        $this->save();

        return $invitationToken;
    }

    public function acceptInvitation()
    {
        $this->invitation_token = null;
        $this->is_disabled = false;
        $this->save();
    }

    public function confirm()
    {
        $this->confirmation_token = null;
        $this->save();
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
            $scope = current_company();
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

    /**
     * Because we pass uuid of the user instead of email address to
     * Passport, we have to describe in the method below how to find
     * user by using our passed value.
     */
    public function findForPassport($uuid)
    {
        // and because uuid is users priamry key, it's quite easy to do :)
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