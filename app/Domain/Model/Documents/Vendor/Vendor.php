<?php

namespace App\Domain\Model\Documents\Vendor;

use App\Domain\Model\Passive\Country;
use App\Domain\Model\Passive\Currency;
use App\Domain\Model\Expense\Expense;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'company_name',
        'registration_number',
        'vat_number',
        'website',
        'phone',
        'logo',
        'adress1',
        'adress2',
        'city',
        'postal_code',
        'state',
        'country_id',
        'currency_id',
        // 'notes'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function getTableData()
    {
        return [
            'uuid' => $this->uuid,

            'company_name' => $this->company_name,
            'registration_number' => $this->registration_number,
            'vat_number' => $this->vat_number,
            'website' => $this->website,
            'phone' => $this->phone,
            'logo' => $this->logo,
            'adress1' => $this->adress1,
            'adress2' => $this->adress2,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'state' => $this->state,
            'country' => $this->country,
            'currency' => $this->currency,
            'notes' => $this->notes,

            'contacts' => $this->contacts->map(function ($contact) { return $contact->getTableData(); }),
            'expenses' => $this->expenses()->sum('amount'),

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
        return $this->belongsTo(Currency::class);
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