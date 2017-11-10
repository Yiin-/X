<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\System\ActivityLog\ActivityRepository;

class RegisterUserActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $action;
    public $document;

    public function __construct(User $user, $action, AbstractDocument $document)
    {
        $this->user = $user;
        $this->action = $action;
        $this->document = $document;
    }

    public function handle(ActivityRepository $activityRepository)
    {
        $this->document->loadRelationships();
        $activityRepository->registerUserActivity($this->user, $this->action, $this->document);
    }
}