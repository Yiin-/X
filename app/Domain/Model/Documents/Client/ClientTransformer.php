<?php

namespace App\Domain\Model\Documents\Client;

use League\Fractal;
use App\Domain\Model\Features\VatChecker\VatInfo;
use App\Domain\Model\Features\VatChecker\VatInfoTransformer;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class ClientTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'contacts',
        'vat_number_checks',
        'history'
    ];

    public function transform(Client $client)
    {
        return [
            'uuid' => $client->uuid,

            // Organization
            'name' => $client->name,
            'registration_number' => $client->registration_number,
            'vat_number' => $client->vat_number,

            'website' => $client->website,
            'phone' => $client->phone,
            'email' => $client->primary_email,

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
            'country' => $client->country,
            'currency_code' => $client->currency_code,
            'currency' => $client->currency,
            'language' => $client->language,
            'company_size' => $client->company_size,
            'industry' => $client->industry,

            'is_disabled' => $client->is_disabled,

            'created_at' => $client->created_at,
            'updated_at' => $client->updated_at,
            'archived_at' => $client->archived_at,
            'deleted_at' => $client->deleted_at
        ];
    }

    public function includeContacts(Client $client)
    {
        return $this->collection($client->contacts, new ClientContactTransformer);
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