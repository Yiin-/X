<?php

namespace App\Domain\Events\Document;

use App\Domain\Events\Traits\BroadcastsToUsers;
use App\Domain\Model\Documents\Shared\AbstractDocument;

class DocumentWasUpdated
{
    use BroadcastsToUsers;

    public $document;

    /**
     * Create a new event instance.
     *
     * @param AbstractDocument $document
     */
    public function __construct(AbstractDocument $document)
    {
        $this->document = $document;

        if (!$document->wasRecentlyCreated) {
            $this->broadcastToUsers($document, false);
        }
    }
}