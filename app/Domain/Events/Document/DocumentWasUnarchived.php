<?php

namespace App\Domain\Events\Document;

use App\Domain\Model\Documents\Shared\AbstractDocument;

class DocumentWasUnarchived
{
    public $user;
    public $document;

    /**
     * Create a new event instance.
     *
     * @param AbstractDocument $document
     */
    public function __construct(AbstractDocument $document)
    {
        $document->loadRelationships();

        $this->user = auth()->user();
        $this->document = $document;
    }
}