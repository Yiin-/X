<?php

namespace App\Domain\Model\Documents\Product;

use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Documents\TaxRate\TaxRate;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'currency_id',
        'qty',
        'tax_rate_uuid',
        'description'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function getTableData()
    {
        return [
            'uuid' => $this->uuid,

            'name' => $this->name,
            'price' => $this->price,
            'currency' => $this->currency,
            'description' => $this->description,
            'qty' => $this->qty,

            'tax_rate' => $this->taxRate,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'archived_at' => $this->archived_at
        ];
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }
}