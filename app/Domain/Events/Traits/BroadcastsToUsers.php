<?php

namespace App\Domain\Events\Traits;

use App\Domain\Constants\Permission\Actions as PermissionActions;
use App\Domain\Events\Shared\BroadcastableEvent;
use App\Domain\Model\Authentication\User\User;

trait BroadcastsToUsers
{
    public function broadcastToOtherUsers($document)
    {
        return $this->broadcastToUsers($document, true);
    }

    public function broadcastToUsers($document, $toOthers = false)
    {
        /**
         * Get list of users, who has permission to view this document...
         */
        $users = User::withPermissionTo(PermissionActions::VIEW, $document)->get();

        /**
         * ... and broadcast event to all of them.
         * Realistically, there shouldn't be more than 100 users per company,
         * but if there are any performance issues, we should think of better
         * implementation.
         */
        foreach ($users as $user) {
            if ($toOthers) {
                broadcast(new BroadcastableEvent(static::class, $user, $document))->toOthers();
            }
            else {
                broadcast(new BroadcastableEvent(static::class, $user, $document));
            }
        }
    }
}