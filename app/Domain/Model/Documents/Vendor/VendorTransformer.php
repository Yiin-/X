<?php

namespace App\Domain\Model\Documents\Vendor;

use League\Fractal;
use App\Domain\Model\Features\VatChecker\VatInfo;
use App\Domain\Model\Features\VatChecker\VatInfoTransformer;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;
use App\Domain\Model\Documents\Contact\ContactTransformer;

class VendorTransformer extends Fractal\TransformerAbstract
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

    public function transform(Vendor $vendor)
    {
        return [
            'uuid' => $vendor->uuid,
            'company_uuid' => $vendor->company_uuid,

            'name' => $vendor->name,
            'registration_number' => $vendor->registration_number,
            'vat_number' => $vendor->vat_number,
            'website' => $vendor->website,
            'phone' => $vendor->primary_phone,
            'email' => $vendor->email,

            'logo' => $vendor->logo,
            'address1' => $vendor->address1,
            'address2' => $vendor->address2,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'state' => $vendor->state,
            'country_id' => $vendor->country_id,
            'currency_code' => $vendor->currency_code,
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
        return $this->collection($vendor->contacts, new ContactTransformer);
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