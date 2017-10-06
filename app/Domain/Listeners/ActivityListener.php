<?php

namespace App\Domain\Listeners;

use App\Domain\Model\System\ActivityLog\ActivityRepository;
use App\Domain\Events\Document\DocumentWasCreated;
use App\Domain\Events\Document\DocumentWasUpdated;
use App\Domain\Events\Document\DocumentWasSaved;
use App\Domain\Events\Document\DocumentWasDeleted;
use App\Domain\Events\Document\DocumentWasRestored;

class ActivityListener
{
    private $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function documentWasCreated(DocumentWasCreated $event)
    {
        $this->activityRepository->registerUserActivity($event->user, 'created', $event->document);
    }

    public function documentWasUpdated(DocumentWasUpdated $event)
    {
        $this->activityRepository->registerUserActivity($event->user, 'updated', $event->document);
    }

    public function documentWasSaved(DocumentWasSaved $event)
    {
        \Log::debug('documentWasSaved');
    }

    public function documentWasDeleted(DocumentWasDeleted $event)
    {
        $this->activityRepository->registerUserActivity($event->user, 'deleted', $event->document);
    }

    public function documentWasRestored(DocumentWasRestored $event)
    {
        $this->activityRepository->registerUserActivity($event->user, 'restored', $event->document);
    }

    public function subscribe($events)
    {
        $events->listen(DocumentWasCreated::class, self::class . '@documentWasCreated');
        $events->listen(DocumentWasUpdated::class, self::class . '@documentWasUpdated');
        $events->listen(DocumentWasDeleted::class, self::class . '@documentWasDeleted');
        $events->listen(DocumentWasRestored::class, self::class . '@documentWasRestored');
        $events->listen(DocumentWasSaved::class, self::class . '@documentWasSaved');
    }
}