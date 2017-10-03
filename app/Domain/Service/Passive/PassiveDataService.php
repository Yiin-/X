<?php

namespace App\Domain\Service\Passive;

use App\Domain\Model\Documents\Passive\CompanySize;
use App\Domain\Model\Documents\Passive\Country;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Passive\Industry;
use App\Domain\Model\Documents\Passive\Language;
use App\Domain\Model\Documents\Passive\Timezone;
use App\Domain\Model\Documents\Passive\PaymentType;

class PassiveDataService
{
    public function getAll()
    {
        return [
            'companySizes' => CompanySize::all(),
            'countries' => Country::all(),
            'currencies' => Currency::all(),
            'industries' => Industry::all(),
            'languages' => Language::all(),
            'timezones' => Timezone::all(),
            'paymentTypes' => PaymentType::all()
        ];
    }
}