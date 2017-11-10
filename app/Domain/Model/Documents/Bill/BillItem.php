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
        'discount',
        'tax_rate_uuid',
        'index'
    ];

    public function getTransformer()
    {
        return new BillItemTransformer;
    }

    protected $dispatchesEvents = [];

    public function getInitialCostAttribute()
    {
        return bcmul($this->qty, $this->cost, 2);
    }

    public function getCostBeforeTaxAttribute()
    {
        $sum = $this->initial_cost;
        $discount = bcdiv(bcmul($sum, $this->discount, 2), 100, 2);

        return bcsub($sum, $discount, 2);
    }

    public function getTaxAttribute()
    {
        $tax = $this->taxRate ? bcdiv($this->taxRate->rate, 100, 2) : 0;

        return bcmul($tax, $this->cost_before_tax, 2);
    }

    public function getFinalPriceAttribute()
    {
        return bcadd($this->tax, $this->cost_before_tax, 2);
    }

    public function billable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }
}