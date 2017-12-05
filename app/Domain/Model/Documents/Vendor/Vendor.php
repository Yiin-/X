<?php

namespace App\Domain\Model\Documents\Vendor;

use App\Domain\Model\Documents\Passive\Country;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Expense\Expense;
use App\Domain\Model\Documents\Contact\Contact;
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
        'email',
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

    protected $with = [
        'contacts'
    ];

    public function getTransformer()
    {
        return new VendorTransformer;
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
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}