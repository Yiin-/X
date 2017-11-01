<?php

namespace App\Domain\Model\Documents\Payment;

use App\Domain\Model\Documents\Company\Company;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Invoice\Invoice;
use App\Domain\Model\Documents\Passive\PaymentType;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends AbstractDocument
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

    public function transform($exclude = [])
    {
        return [
            'uuid' => $this->uuid,

            'client_uuid' => $this->client_uuid,
            'invoice_uuid' => $this->invoice_uuid,

            'amount' => +$this->amount,
            'refunded' => +$this->refunded,
            'currency' => $this->currency,
            'payment_type' => $this->paymentType,
            'payment_date' => $this->payment_date,
            'payment_reference' => $this->payment_reference,

            'is_disabled' => $this->is_disabled,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'archived_at' => $this->archived_at
        ];
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