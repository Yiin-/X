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
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\Payment\Payment;
use App\Domain\Model\Documents\Credit\Credit;
use App\Domain\Model\Documents\Quote\Quote;
use App\Domain\Model\Documents\Expense\Expense;
use App\Domain\Model\Documents\Contact\Contact;
use App\Domain\Model\CRM\Project\Project;
use App\Domain\Model\Features\VatChecker\VatInfo;

class Client extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'registration_number',
        'vat_number',
        'website',
        'email',
        'description',
        'address1',
        'address2',
        'city',
        'state',
        'postal_code',
        'payment_terms',
        'country_id',
        'currency_code',
        'language_id',
        'company_size_id',
        'industry_id'
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
        return new ClientTransformer;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function hasPrimaryPhone()
    {
        return $this->primary_phone;
    }

    public function getPrimaryPhoneAttribute()
    {
        $primaryContact = $this->contacts()->first();

        return $primaryContact ? $primaryContact->phone : '';
    }
}