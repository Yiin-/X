<?php

namespace App\Domain\Listeners;

use App\Domain\Model\System\ActivityLog\ActivityRepository;
use App\Domain\Events\Document\UserCreatedDocument;
use App\Domain\Events\Document\UserUpdatedDocument;
use App\Domain\Events\Document\DocumentWasSaved;
use App\Domain\Events\Document\DocumentWasDeleted;
use App\Domain\Events\Document\DocumentWasRestored;
use App\Domain\Events\Document\DocumentWasArchived;
use App\Domain\Events\Document\DocumentWasUnarchived;

class ActivityListener
{
    private $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function accountWasCreated(AccountWasCreated $event)
    {
        $this->activityRepository->registerUserActivity(null, 'created', $event->account);
    }

    public function accountWasUpdated(AccountWasUpdated $event)
    {
        $this->activityRepository->registerUserActivity(null, 'updated', $event->account);
    }

    public function documentWasCreated(UserCreatedDocument $event)
    {
        $this->activityRepository->registerUserActivity($event->user, 'created', $event->document);
    }

    public function documentWasUpdated(UserUpdatedDocument $event)
    {
       if ($event->document->restoredFromActivity) {
            $this->activityRepository->registerUserActivity($event->user, 'restored', $event->document, [
                'restoredFromActivity' => $event->document->restoredFromActivity
            ]);
        }
        else {
            $this->activityRepository->registerUserActivity($event->user, 'updated', $event->document);
        }
    }

    public function documentWasArchived(DocumentWasArchived $event)
    {
        $this->activityRepository->registerUserActivity($event->user, 'archived', $event->document);
    }

    public function documentWasUnarchived(DocumentWasUnarchived $event)
    {
        $this->activityRepository->registerUserActivity($event->user, 'unarchived', $event->document);
    }

    public function documentWasDeleted(DocumentWasDeleted $event)
    {
        $this->activityRepository->registerUserActivity($event->user, 'deleted', $event->document);
    }

    public function documentWasRestored(DocumentWasRestored $event)
    {
        $this->activityRepository->registerUserActivity($event->user, 'recovered', $event->document);
    }

    public function subscribe($events)
    {
        $events->listen(AccountWasCreated::class, self::class . '@accountWasCreated');
        $events->listen(AccountWasUpdated::class, self::class . '@accountWasUpdated');

        $events->listen(UserCreatedDocument::class, self::class . '@documentWasCreated');
        $events->listen(UserUpdatedDocument::class, self::class . '@documentWasUpdated');
        $events->listen(DocumentWasDeleted::class, self::class . '@documentWasDeleted');
        $events->listen(DocumentWasRestored::class, self::class . '@documentWasRestored');
        $events->listen(DocumentWasArchived::class, self::class . '@documentWasArchived');
        $events->listen(DocumentWasUnarchived::class, self::class . '@documentWasUnarchived');
    }
}