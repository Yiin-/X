<?php

namespace App\Interfaces\Http\Controllers\Documents;

use App\Interfaces\Http\Controllers\DocumentController;
use App\Domain\Model\Documents\Expense\ExpenseCategoryRepository;

class ExpenseCategoryController extends DocumentController
{
    protected $repository;

    public function __construct(ExpenseCategoryRepository $expenseCategoryRepository)
    {
        $this->repository = $expenseCategoryRepository;
    }

    public function getResourceName()
    {
        return 'expense-category';
    }

    public function getValidationRules($action)
    {
        $rules = [
            static::VALIDATION_RULES_CREATE => [
                $this->getResourceName() => 'required|array',
                "{$this->getResourceName()}.name" => 'required'
            ],
            static::VALIDATION_RULES_PATCH => [
                $this->getResourceName() => 'required|array'
            ]
        ];
        $rules[static::VALIDATION_RULES_UPDATE] = $rules[static::VALIDATION_RULES_CREATE];

        return $rules[$action];
    }

    public function getValidationAttributes()
    {
        return [
            "{$this->getResourceName()}.name" => 'categorie\'s name'
        ];
    }
}