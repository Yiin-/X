<?php

namespace App\Domain\Observers\Documents\Traits;

use App\Domain\Model\Documents\Shared\AbstractDocument;

trait DisablesChildren
{
    public function deleting(AbstractDocument $document)
    {
        if ($document->isForceDeleting()) {
            foreach ($this->children as $childrenRelationship) {
                foreach ($document->{$childrenRelationship} as $relatedDocument) {
                    $relatedDocument->forceDelete();
                }
            }
        } else {
            foreach ($this->children as $childrenRelationship) {
                foreach ($document->{$childrenRelationship} as $relatedDocument) {
                    $relatedDocument->is_disabled = true;
                    $relatedDocument->save();
                }
            }
        }
    }

    public function restored(AbstractDocument $document)
    {
        foreach ($this->children as $childrenRelationship) {
            foreach ($document->{$childrenRelationship} as $relatedDocument) {
                $relatedDocument->is_disabled = false;
                $relatedDocument->save();
            }
        }
    }
}