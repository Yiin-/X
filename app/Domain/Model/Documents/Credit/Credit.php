<?php

namespace App\Domain\Model\Documents\Credit;

use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class Credit extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'client_uuid',
        'amount',
        'currency_code',
        'balance',
        'credit_date',
        'credit_number'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function transform()
    {
        return [
            'uuid' => $this->uuid,

            'relationships' => [
                'client' => $this->client_uuid,
            ],

            'amount' => $this->amount,
            'currency' => $this->currency,
            'balance' => $this->balance,
            'credit_date' => $this->credit_date,
            'credit_number' => $this->credit_number,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'archived_at' => $this->archived_at,
            'deleted_at' => $this->deleted_at
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}