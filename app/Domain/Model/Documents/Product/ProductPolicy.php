<?php

namespace App\Domain\Model\Documents\Product;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class ProductPolicy
{
    /**
     * Determine if user can view list of products.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Product::class);
    }

    /**
     * Determine if user can see given product.
     *
     * @param  User $user
     * @param  Product $product
     * @return bool
     */
    public function see(User $user, Product $product)
    {
        return $user->hasPermissionTo(Actions::VIEW, $product);
    }

    /**
     * Determine if user can create a product.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Product::class);
    }

    /**
     * Determine if the given product can be updated by the user.
     *
     * @param  User   $user
     * @param  Product $product
     * @return bool
     */
    public function update(User $user, Product $product)
    {
        return $user->hasPermissionTo(Actions::EDIT, $product);
    }

    /**
     * Determine if the given product can be deleted by the user.
     *
     * @param  User   $user
     * @param  Product $product
     * @return bool
     */
    public function delete(User $user, Product $product)
    {
        return $user->hasPermissionTo(Actions::DELETE, $product);
    }

    /**
     * Determine if the given product can be archived by the user.
     *
     * @param  User   $user
     * @param  Product $product
     * @return bool
     */
    public function archive(User $user, Product $product)
    {
        return $user->hasPermissionTo(Actions::ARCHIVE, $product);
    }
}