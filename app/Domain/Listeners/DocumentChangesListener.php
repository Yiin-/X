<?php

namespace App\Domain\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domain\Constants\Permission\Actions as PermissionActions;
use App\Domain\Events\Shared\BroadcastableEvent;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Events\Document\UserCreatedDocument;
use App\Domain\Events\Document\UserUpdatedDocument;
use App\Domain\Events\Document\DocumentWasCreated;
use App\Domain\Events\Document\DocumentWasUpdated;
use App\Domain\Events\Document\DocumentWasSaved;
use App\Domain\Events\Document\DocumentWasDeleted;
use App\Domain\Events\Document\DocumentWasRestored;
use App\Domain\Events\Document\DocumentWasArchived;
use App\Domain\Events\Document\DocumentWasUnarchived;
use App\Domain\Events\Authorization\RoleWasSaved;

class DocumentChangesListener implements ShouldQueue
{
    public function subscribe($events)
    {
        $events->listen(DocumentWasCreated::class, self::class . '@broadcastToUsers');
        $events->listen(UserCreatedDocument::class, self::class . '@broadcastToUsers');
        $events->listen(DocumentWasUpdated::class, self::class . '@broadcastToUsers');
        $events->listen(UserUpdatedDocument::class, self::class . '@broadcastToUsers');
        $events->listen(DocumentWasDeleted::class, self::class . '@broadcastToOtherUsers');
        $events->listen(DocumentWasRestored::class, self::class . '@broadcastToOtherUsers');
        $events->listen(DocumentWasArchived::class, self::class . '@broadcastToOtherUsers');
        $events->listen(DocumentWasUnarchived::class, self::class . '@broadcastToOtherUsers');
    }

    /**
     * Do not broadcast to current user
     */
    public function broadcastToOtherUsers($event)
    {
        return $this->handleBroadcast($event, $event->document, true);
    }

    /**
     * Broadcast to all users
     */
    public function broadcastToUsers($event)
    {
        $this->handleBroadcast($event, $event->document, false);
    }

    private function handleBroadcast($event, $document, $toOthers)
    {
        /**
         * Get fresh data about document relationships
         */
        $document->loadRelationships();

        \Log::debug('broadcasting ' . get_class($document) . ': ' . $document->uuid);

        /**
         * Get list of users, who has permission to view this document...
         */
        $users = User::withPermissionTo(PermissionActions::VIEW, $document)->get();

        if ($document instanceof Company) {
            $users = $users->merge($document->users);
        }
        else if ($document instanceof Role) {
            $users = $users->merge($document->users);
        }

        /**
         * ... and broadcast event to all of them.
         */
        foreach ($users as $user) {
            if ($toOthers) {
                broadcast(new BroadcastableEvent(get_class($event), $user, $document))->toOthers();
            }
            else {
                broadcast(new BroadcastableEvent(get_class($event), $user, $document));
            }
        }
    }
}