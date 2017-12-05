<?php

namespace App\Domain\Model\Documents\Expense;

use League\Fractal;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class ExpenseTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'history'
    ];

    public function excludeForBackup()
    {
        return ['history'];
    }

    public function transform(Expense $expense)
    {
        return [
            'uuid' => $expense->uuid,
            'company_uuid' => $expense->company_uuid,

            'vendor_uuid' => $expense->vendor_uuid,
            'client_uuid' => $expense->client_uuid,
            'invoice_uuid' => $expense->invoice ? $expense->invoice->uuid : null,

            'amount' => +$expense->amount,
            'currency_code' => $expense->currency_code,
            'date' => $expense->date,

            'is_disabled' => $expense->is_disabled,

            'created_at' => $expense->created_at,
            'updated_at' => $expense->updated_at,
            'archived_at' => $expense->archived_at,
            'deleted_at' => $expense->deleted_at
        ];
    }

    public function includeHistory(Expense $expense)
    {
        return $this->collection($expense->getHistory(), new ActivityTransformer);
    }
}