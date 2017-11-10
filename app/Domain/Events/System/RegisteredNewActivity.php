<?php

namespace App\Domain\Events\System;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Domain\Constants\Permission\Actions as PermissionActions;
use App\Domain\Events\Broadcast\BroadcastActivity;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\System\ActivityLog\Activity;

class RegisteredNewActivity
{
    public $user;
    public $activity;

    /**
     * Create a new event instance.
     *
     * @param AbstractDocument $document
     */
    public function __construct(Activity $activity)
    {
        if (!$activity->user) {
            // new account created
            return;
        }
        $this->user = $activity->user;
        $this->activity = $activity;

        if ($activity->document) {
            $users = User::withPermissionTo(PermissionActions::VIEW, $activity->document)->get();

            foreach ($users as $user) {
                broadcast(new BroadcastActivity(static::class, $user, $activity));
            }
        }
        else {
            broadcast(new BroadcastActivity(static::class, $this->user, $activity));
        }
    }
}