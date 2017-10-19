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
            $amount += $item->cost;
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
            $amount += $item->discount;
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
            $amount += $item->tax;
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
            $amount += $item->final_price;
        }

        switch ($this->bill->discount_type) {
            case DiscountTypes::FLAT:
                $amount -= $this->bill->discount;
                break;
            case DiscountTypes::PERCENTAGE:
                $amount -= $amount * ($this->bill->discount / 100);
                break;
        }

        return round($amount, 2);
    }

    /**
     * Every billable document should have reference to actual bill
     */
    public function bill()
    {
        return $this->morphOne(Bill::class, 'billable', null, 'billable_uuid');
    }
}