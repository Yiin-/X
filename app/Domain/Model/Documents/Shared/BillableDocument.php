<?php

namespace App\Domain\Model\Documents\Shared;

use App\Domain\Constants\Bill\DiscountTypes;
use App\Domain\Model\Documents\Bill\BillItem;
use App\Domain\Model\Documents\Pdf\Pdf;
use App\Domain\Model\Documents\Credit\AppliedCredit;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Passive\Currency;

class BillableDocument extends AbstractDocument
{
    protected $fillable = [
        'date',
        'due_date',
        'archived_at'
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function items()
    {
        return $this->morphMany(BillItem::class, 'billable')->orderBy('index', 'asc');
    }

    public function appliedCredits()
    {
        return $this->morphMany(AppliedCredit::class, 'billable');
    }

    public function getAppliedCreditsSumAttribute()
    {
        return $this->appliedCredits->reduce(function ($sum, $appliedCredit) {
            return bcadd($sum, convert_currency($appliedCredit->amount, $appliedCredit->currency_code, $this->currency_code), 2);
        }, 0);
    }

    public function getTransformer()
    {
        //
    }

    /**
     * Generated document pdfs
     */
    public function pdfs()
    {
        return $this->morphMany(Pdf::class, 'pdfable');
    }

    /**
     * Calculate amount before discount and taxes
     */
    public function subTotal()
    {
        $amount = 0;

        foreach ($this->items as $item) {
            $amount = bcadd($amount, $item->initial_cost, 2);
        }

        return +$amount;
    }

    /**
     * Sum items discount
     */
    public function discount()
    {
        $amount = 0;

        foreach ($this->items as $item) {
            $amount = bcadd($amount, $item->discount, 2);
        }

        return +$amount;
    }

    /**
     * Sum items taxes
     */
    public function taxes()
    {
        $amount = 0;

        foreach ($this->items as $item) {
            $amount = bcadd($amount, $item->tax, 2);
        }

        return +$amount;
    }

    /**
     * Sum final prices of each item and apply
     * global discount.
     */
    public function amount()
    {
        $amount = 0;

        foreach ($this->items as $item) {
            $amount = bcadd($amount, $item->final_price, 2);
        }

        switch ($this->discount_type) {
            case DiscountTypes::FLAT:
                $amount = bcsub($amount, $this->discount_value, 2);
                break;
            case DiscountTypes::PERCENTAGE:
                $amount = bcsub($amount, bcmul($amount, bcdiv($this->discount_value, 100, 2), 2), 2);
                break;
        }

        return +$amount;
    }
}