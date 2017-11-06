<?php

namespace App\Domain\Model\Documents\Shared;

use App\Domain\Constants\Bill\DiscountTypes;
use App\Domain\Model\Documents\Bill\Bill;
use App\Domain\Model\Documents\Pdf\Pdf;

class BillableDocument extends AbstractDocument
{
    public function transform()
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

        foreach ($this->bill->items as $item) {
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

        foreach ($this->bill->items as $item) {
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

        foreach ($this->bill->items as $item) {
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

        foreach ($this->bill->items as $item) {
            $amount = bcadd($amount, $item->final_price, 2);
        }

        switch ($this->bill->discount_type) {
            case DiscountTypes::FLAT:
                $amount = bcsub($amount, $this->bill->discount, 2);
                break;
            case DiscountTypes::PERCENTAGE:
                $amount = bcsub($amount, bcmul($amount, bcdiv($this->bill->discount, 100, 2), 2), 2);
                break;
        }

        $amount = bcsub($amount, $this->bill->applied_credits_sum, 2);

        return +$amount;
    }

    /**
     * Every billable document should have reference to actual bill
     */
    public function bill()
    {
        return $this->morphOne(Bill::class, 'billable', null, 'billable_uuid');
    }
}