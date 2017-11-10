<?php

namespace App\Domain\Model\Documents\Credit;

use League\Fractal;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class CreditTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'history'
    ];

    public function transform(Credit $credit)
    {
        return [
            'uuid' => $credit->uuid,

            'client_uuid' => $credit->client_uuid,
            'amount' => +$credit->amount,
            'currency' => $credit->currency,
            'balance' => +$credit->balance,
            'credit_date' => $credit->credit_date,
            'credit_number' => $credit->credit_number,

            'is_disabled' => $credit->is_disabled,

            'created_at' => $credit->created_at,
            'updated_at' => $credit->updated_at,
            'deleted_at' => $credit->deleted_at,
            'archived_at' => $credit->archived_at
        ];
    }

    public function includeHistory(Credit $credit)
    {
        return $this->collection($credit->getHistory(), new ActivityTransformer);
    }
}