<?php

namespace App\Domain\Model\Documents\Expense;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class ExpenseCategoryPolicy
{
    /**
     * Determine if user can view list of expenses.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, ExpenseCategory::class);
    }

    /**
     * Determine if user can see given expense category.
     *
     * @param  User $user
     * @param  ExpenseCategory $expenseCategory
     * @return bool
     */
    public function see(User $user, ExpenseCategory $expenseCategory)
    {
        return $user->hasPermissionTo(Actions::VIEW, $expenseCategory);
    }

    /**
     * Determine if user can create a expense category.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, ExpenseCategory::class);
    }

    /**
     * Determine if the given expense category can be updated by the user.
     *
     * @param  User   $user
     * @param  ExpenseCategory $expenseCategory
     * @return bool
     */
    public function update(User $user, ExpenseCategory $expenseCategory)
    {
        return $user->hasPermissionTo(Actions::EDIT, $expenseCategory);
    }

    /**
     * Determine if the given expense category can be deleted by the user.
     *
     * @param  User   $user
     * @param  ExpenseCategory $expenseCategory
     * @return bool
     */
    public function delete(User $user, ExpenseCategory $expenseCategory)
    {
        return $user->hasPermissionTo(Actions::DELETE, $expenseCategory);
    }

    /**
     * Determine if the given expense category can be archived by the user.
     *
     * @param  User   $user
     * @param  ExpenseCategory $expenseCategory
     * @return bool
     */
    public function archive(User $user, ExpenseCategory $expenseCategory)
    {
        return $user->hasPermissionTo(Actions::EDIT, $expenseCategory);
    }
}