<?php

namespace App\Domain\Model\Documents\Expense;

use App\Domain\Model\Documents\Vendor\Vendor;
use App\Domain\Model\Documents\Passive\Currency;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends AbstractDocument
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'user_uuid',
        'company_uuid'
    ];

    public function getTableData()
    {
        return [
            'uuid' => $this->uuid,
            'relationships' => [
                'expenses' => $this->expenses->map(function ($expense) {
                    return $expense->uuid
                }),
            ],

            'name' => $this->name,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'archived_at' => $this->archived_at,
            'deleted_at' => $this->deleted_at
        ];
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}