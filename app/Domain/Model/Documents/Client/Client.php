<?php

namespace App\Domain\Model\Documents\Client;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authorization\Permission\Permission;
use App\Domain\Model\Documents\Passive\CompanySize;
use App\Domain\Model\Documents\Passive\Country;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Passive\Industry;
use App\Domain\Model\Documents\Passive\Language;
use App\Domain\Model\Features\VatChecker\VatCheck;

class Client extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'registration_number',
        'vat_number',
        'website',
        'phone',
        'description',
        'address1',
        'address2',
        'city',
        'state',
        'postal_code',
        'payment_terms',
        'country_id',
        'currency_id',
        'language_id',
        'company_size_id',
        'industry_id'
        // 'notes'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function transform()
    {
        $firstContact = $this->contacts()->first();

        $vatStatus = VatCheck::where([
            'country_code' => mb_substr($this->vat_number ?? '', 0, 2),
            'number' => mb_substr($this->vat_number ?? '', 2)
        ])->first();

        return [
            // ID
            'uuid' => $this->uuid,

            // Organization
            'name' => $this->name,
            'registration_number' => $this->registration_number,
            'vat_number' => $this->vat_number,
            'vat_status' => $vatStatus ? $vatStatus->status : null,

            'website' => $this->website,
            'phone' => $this->phone,
            'email' => $firstContact ? $firstContact->profile->email : '',

            // Address
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'state' => $this->state,

            // Additional info
            'payment_terms' => $this->payment_terms,
            'notes' => $this->notes,

            // Relationships
            'country' => $this->country,
            'contacts' => $this->exists ? $this->contacts->map(function ($contact) { return $contact->transform(); }) : $this->contacts,
            'currency' => $this->currency,
            'language' => $this->language,
            'company_size' => $this->company_size,
            'industry' => $this->industry,

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

    public function contacts()
    {
        return $this->hasMany(ClientContact::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function company_size()
    {
        return $this->belongsTo(CompanySize::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

}