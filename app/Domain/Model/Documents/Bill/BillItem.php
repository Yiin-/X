<?php

namespace App\Domain\Model\Documents\Bill;

use App\Domain\Model\Documents\Product\Product;
use App\Domain\Model\Documents\TaxRate\TaxRate;
use App\Domain\Model\Documents\Shared\AbstractDocument;

class BillItem extends AbstractDocument
{
    protected $fillable = [
        'billable_type',
        'billable_id',
        'product_uuid',
        'name',
        'identification_number',
        'cost',
        'qty',
        'discount_type',
        'discount_value',
        'tax_rate_uuid',
        'index'
    ];

    protected $dispatchesEvents = [];

    public function getTransformer()
    {
        return new BillItemTransformer;
    }

    /**
     * Full cost
     */
    public function getInitialCostAttribute()
    {
        return bcmul($this->qty, $this->cost, 2);
    }

    /**
     * Full cost - discount
     */
    public function getCostBeforeTaxAttribute()
    {
        $sum = $this->initial_cost;
        $discount = bcdiv(bcmul($sum, $this->discount, 2), 100, 2);

        return bcsub($sum, $discount, 2);
    }

    /**
     * (Full cost * tax%)
     */
    public function getTaxAttribute()
    {
        $tax = $this->taxRate ? bcdiv($this->taxRate->rate, 100, 2) : 0;

        return bcmul($tax, $this->cost_before_tax, 2);
    }

    /**
     * Full cost + tax
     */
    public function getFinalPriceAttribute()
    {
        return bcadd($this->tax, $this->cost_before_tax, 2);
    }

    /**
     * Invoice / Quote / whatever
     */
    public function billable()
    {
        return $this->morphTo();
    }

    /**
     * Saved product / service
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * not used at the moment
     */
    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }
}