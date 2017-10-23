<?php

namespace App\Domain\Model\Documents\Vendor;

use App\Domain\Model\Documents\Passive\Country;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Expense\Expense;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'registration_number',
        'vat_number',
        'website',
        'phone',
        'logo',
        'address1',
        'address2',
        'city',
        'postal_code',
        'state',
        'country_id',
        'currency_code',
        // 'notes'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function transform()
    {
        $firstContact = $this->contacts()->first();

        return [
            'uuid' => $this->uuid,

            'name' => $this->name,
            'registration_number' => $this->registration_number,
            'vat_number' => $this->vat_number,
            'website' => $this->website,
            'phone' => $this->phone,
            'email' => $firstContact ? $firstContact->profile->email : '',
            'logo' => $this->logo,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'state' => $this->state,
            'country' => $this->country,
            'currency' => $this->currency,
            'notes' => $this->notes,

            'contacts' => $this->exists ? $this->contacts->map(function ($contact) { return $contact->transform(); }) : $this->contacts,
            'expenses' => $this->expenses()->sum('amount'),

            'is_disabled' => $this->is_disabled,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'archived_at' => $this->archived_at,
            'deleted_at' => $this->deleted_at
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function contacts()
    {
        return $this->hasMany(VendorContact::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}