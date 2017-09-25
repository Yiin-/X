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
        'cost',
        'qty',
        'discount',
        'tax_rate_uuid',
        'index'
    ];

    public function getTableData()
    {
        return [
            'product' => $this->product->getTableData(),
            'cost' => $this->cost,
            'discount' => $this->discount,
            'qty' => $this->qty,
            'tax_rate' => $this->taxRate,
            'index' => $this->index
        ];
    }

    public function getFinalPriceAttribute()
    {
        $sum = $this->qty * $this->cost;
        $discount = $sum * $this->discount / 100;
        $cost = $sum - $discount;
        $tax = $this->taxRate ? ($this->taxRate->rate / 100) : 0;

        return ($tax * $cost) + $cost;
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