<?php

namespace App\Domain\Model\Documents\Client;

use League\Fractal;
use App\Domain\Model\Features\VatChecker\VatInfo;
use App\Domain\Model\Features\VatChecker\VatInfoTransformer;
use App\Domain\Model\Documents\Contact\ContactTransformer;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;
use App\Domain\Model\Authorization\Company\Company;

class ClientTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'vat_number_checks',
        'history',
        'contacts'
    ];

    public function excludeForBackup()
    {
        return ['vat_number_checks', 'history'];
    }

    public function transform(Client $client)
    {
        return [
            'uuid' => $client->uuid,
            'company_uuid' => $client->company_uuid,

            // Organization
            'name' => $client->name,
            'registration_number' => $client->registration_number,
            'vat_number' => $client->vat_number,

            'website' => $client->website,
            'phone' => $client->primary_phone,
            'email' => $client->email,

            // Address
            'address1' => $client->address1,
            'address2' => $client->address2,
            'city' => $client->city,
            'postal_code' => $client->postal_code,
            'state' => $client->state,

            // Additional info
            'payment_terms' => $client->payment_terms,
            'notes' => $client->notes,

            // Relationships
            'country_id' => $client->country_id,
            'currency_code' => $client->currency_code,
            'language_id' => $client->language_id,
            'company_size_id' => $client->company_size_id,
            'industry_id' => $client->industry_id,

            'is_disabled' => $client->is_disabled,

            'created_at' => $client->created_at,
            'updated_at' => $client->updated_at,
            'archived_at' => $client->archived_at,
            'deleted_at' => $client->deleted_at
        ];
    }

    public function includeContacts(Client $client)
    {
        return $this->collection($client->contacts, new ContactTransformer);
    }

    public function includeVatNumberChecks(Client $client)
    {
        $vatChecks = VatInfo::whereRaw('CONCAT(country_code, number) = ?', [$client->vat_number])->get();

        return $this->collection($vatChecks, new VatInfoTransformer);
    }

    public function includeHistory(Client $client)
    {
        return $this->collection($client->getHistory(), new ActivityTransformer);
    }
}