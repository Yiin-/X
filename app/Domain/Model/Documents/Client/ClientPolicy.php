<?php

namespace App\Domain\Model\Documents\Client;

use App\Domain\Constants\Permission\Actions;
use App\Domain\Model\Authentication\User\User;

class ClientPolicy
{
    /**
     * Determine if user can view list of clients.
     *
     * @param  User   $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo(Actions::VIEW, Client::class);
    }

    /**
     * Determine if user can see given client.
     *
     * @param  User $user
     * @param  Client $client
     * @return bool
     */
    public function see(User $user, Client $client)
    {
        return $user->hasPermissionTo(Actions::VIEW, $client);
    }

    /**
     * Determine if user can create a client.
     *
     * @param  User   $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo(Actions::CREATE, Client::class);
    }

    /**
     * Determine if the given client can be updated by the user.
     *
     * @param  User   $user
     * @param  Client $client
     * @return bool
     */
    public function update(User $user, Client $client)
    {
        return $user->hasPermissionTo(Actions::EDIT, $client);
    }

    /**
     * Determine if the given client can be deleted by the user.
     *
     * @param  User   $user
     * @param  Client $client
     * @return bool
     */
    public function delete(User $user, Client $client)
    {
        return $user->hasPermissionTo(Actions::DELETE, $client);
    }

    /**
     * Determine if the given client can be archived by the user.
     *
     * @param  User   $user
     * @param  Client $client
     * @return bool
     */
    public function archive(User $user, Client $client)
    {
        return $user->hasPermissionTo(Actions::ARCHIVE, $client);
    }
}