<?php

namespace App\Domain\Events\Broadcast;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\System\ActivityLog\Activity;

class BroadcastActivity implements ShouldBroadcast
{
    use InteractsWithSockets;

    public $event;
    public $user;
    public $activity;

    /**
     * Create a new event instance.
     *
     * @param AbstractDocument $document
     */
    public function __construct($event, User $user, Activity $activity)
    {
        $this->event = $event;
        $this->user = $user;
        $this->activity = $activity;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user:' . $this->user->account_uuid . '.' . $this->user->uuid);
    }

    /**
     * The event's broadcast name.
     *
     * This value gets cached, so I don't think we can specify
     * what exactly event we're currently broadcasting.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'system.event';
    }
}