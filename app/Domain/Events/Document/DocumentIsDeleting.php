<?php

namespace App\Domain\Events\Document;

use App\Domain\Model\Documents\Shared\AbstractDocument;

class DocumentIsDeleting
{
    public $document;

    /**
     * Create a new event instance.
     *
     * @param AbstractDocument $document
     */
    public function __construct(AbstractDocument $document)
    {
        $this->document = $document;
    }
}