<?php

namespace App\Domain\Model\Documents\Product;

use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Documents\TaxRate\TaxRate;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\Fractal;

class Product extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'qty',
        'description',
        'identification_number',

        'price',
        'currency_code',
        'tax_rate_uuid'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function transform()
    {
        return (new Fractal\Manager)->createData(new Fractal\Resource\Item($this, new ProductTransformer))->toArray()['data'];
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }
}