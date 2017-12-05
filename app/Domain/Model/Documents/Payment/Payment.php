<?php

namespace App\Domain\Model\Documents\Payment;

use App\Domain\Model\Documents\Shared\Interfaces\BelongsToClient;
use App\Domain\Model\Documents\Company\Company;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\Passive\PaymentType;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends AbstractDocument implements BelongsToClient
{
    use SoftDeletes;

    protected $fillable = [
        'client_uuid',
        'invoice_uuid',
        'amount',
        'refunded',
        'currency_code',
        'payment_type_id',
        'payment_date',
        'payment_reference'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    protected $dates = [
        'payment_date',
        'created_at',
        'updated_at',
        'deleted_at',
        'archived_at'
    ];

    protected $touches = ['invoice'];

    public function getTransformer()
    {
        return new PaymentTransformer;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}