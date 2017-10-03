<?php

namespace App\Domain\Events\Document;

use App\Domain\Events\Traits\BroadcastsToUsers;
use App\Domain\Model\Documents\Shared\AbstractDocument;

class DocumentWasSaved
{
    use BroadcastsToUsers;

    public $user;
    public $document;

    /**
     * Create a new event instance.
     *
     * @param AbstractDocument $document
     */
    public function __construct(AbstractDocument $document)
    {
        $this->user = auth()->user();
        $this->document = $document;
    }
}