<?php

namespace App\Domain\Model\Documents\Expense;

use League\Fractal;
use App\Domain\Model\System\ActivityLog\ActivityTransformer;

class ExpenseCategoryTransformer extends Fractal\TransformerAbstract
{
    protected $defaultIncludes = [
        'history'
    ];

    public function excludeForBackup()
    {
        return ['history'];
    }

    public function transform(ExpenseCategory $expenseCategory)
    {
        return [
            'uuid' => $expenseCategory->uuid,
            'company_uuid' => $expenseCategory->company_uuid,

            'name' => $expenseCategory->name,

            'created_at' => $expenseCategory->created_at,
            'updated_at' => $expenseCategory->updated_at,
            'archived_at' => $expenseCategory->archived_at,
            'deleted_at' => $expenseCategory->deleted_at
        ];
    }

    public function includeHistory(ExpenseCategory $expenseCategory)
    {
        return $this->collection($expenseCategory->getHistory(), new ActivityTransformer);
    }
}