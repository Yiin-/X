<?php

namespace App\Domain\Events\Shared;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\User\User;

class BroadcastableEvent implements ShouldBroadcast
{
    use InteractsWithSockets;

    private $user;

    public $event;
    public $documentName;
    public $documentModel;

    public function __construct($event, User $user, AbstractDocument $document)
    {
        $this->user = $user;

        /**
         * Define broadcastable event here, see broadcastAs() method comment.
         */
        $this->event = $event;
        $this->documentName = class_basename($document);
        $this->documentModel = $document->transform();
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
        return 'document.event';
    }
}
