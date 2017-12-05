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

    public function getTransformer()
    {
        return new ExpenseCategoryTransformer;
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}