<?php

namespace App\Domain\Model\Documents\Shared;

class DocumentTransformer
{
    public function transform(AbstractDocument $document)
    {
        return array_merge([
            'uuid' => $document->uuid,

            'created_at' => $document->created_at,
            'updated_at' => $document->updated_at,
            'archived_at' => $document->archived_at,
            'deleted_at' => $document->deleted_at
        ], $this->map($document));
    }
}