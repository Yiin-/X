<?php

namespace App\Domain\Events\Document;

use App\Domain\Events\Traits\BroadcastsToUsers;
use App\Domain\Model\Documents\Shared\AbstractDocument;

class DocumentWasCreated
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
        \Log::debug('document was created');

        $this->broadcastToUsers($document);
    }
}