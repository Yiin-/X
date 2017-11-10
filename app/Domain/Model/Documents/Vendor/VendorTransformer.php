<?php

namespace App\Domain\Model\Documents\Vendor;

use League\Fractal;
use App\Domain\Model\Features\VatChecker\VatInfo;
use App\Domain\Model\Features\VatChecker\VatInfoTransformer;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class VendorTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'contacts',
        'vat_number_checks',
        'history'
    ];

    public function transform(Vendor $vendor)
    {
        return [
            'uuid' => $vendor->uuid,

            'name' => $vendor->name,
            'registration_number' => $vendor->registration_number,
            'vat_number' => $vendor->vat_number,
            'website' => $vendor->website,
            'phone' => $vendor->phone,
            'logo' => $vendor->logo,
            'address1' => $vendor->address1,
            'address2' => $vendor->address2,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'state' => $vendor->state,
            'country' => $vendor->country,
            'currency' => $vendor->currency,
            'notes' => $vendor->notes,

            'expenses' => $vendor->expenses()->sum('amount'),

            'is_disabled' => $vendor->is_disabled,

            'created_at' => $vendor->created_at,
            'updated_at' => $vendor->updated_at,
            'archived_at' => $vendor->archived_at,
            'deleted_at' => $vendor->deleted_at
        ];
    }

    public function includeContacts(Vendor $vendor)
    {
        return $this->collection($vendor->contacts, new VendorContactTransformer);
    }

    public function includeVatNumberChecks(Vendor $vendor)
    {
        $vatChecks = VatInfo::whereRaw('CONCAT(country_code, number) = ?', [$vendor->vat_number])->get();

        return $this->collection($vatChecks, new VatInfoTransformer);
    }

    public function includeHistory(Vendor $vendor)
    {
        return $this->collection($vendor->getHistory(), new ActivityTransformer);
    }
}