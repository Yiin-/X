<?php

namespace App\Domain\Model\Documents\Bill;

use App\Domain\Model\Documents\Product\Product;
use App\Domain\Model\Documents\TaxRate\TaxRate;
use App\Domain\Model\Documents\Shared\AbstractDocument;

class BillItem extends AbstractDocument
{
    protected $fillable = [
        'bill_id',
        'product_uuid',
        'name',
        'cost',
        'qty',
        'discount',
        'tax_rate_uuid',
        'index'
    ];

    protected $touches = [
        'bill'
    ];

    public function transform()
    {
        return [
            'product' => $this->product ? $this->product->transform() : null,
            'name' => $this->name,
            'cost' => $this->cost,
            'discount' => $this->discount,
            'qty' => $this->qty,
            'tax_rate' => $this->taxRate,
            'index' => $this->index
        ];
    }

    protected $dispatchesEvents = [];

    public function getCostBeforeTaxAttribute()
    {
        $sum = $this->qty * $this->cost;
        $discount = $sum * $this->discount / 100;

        return $sum - $discount;
    }

    public function getTaxAttribute()
    {
        $tax = $this->taxRate ? ($this->taxRate->rate / 100) : 0;

        return $tax * $this->cost_before_tax;
    }

    public function getFinalPriceAttribute()
    {
        return $this->tax + $this->cost_before_tax;
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
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