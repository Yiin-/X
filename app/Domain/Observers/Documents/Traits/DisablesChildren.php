<?php

namespace App\Domain\Observers\Documents\Traits;

use App\Domain\Model\Documents\Shared\AbstractDocument;

trait DisablesChildren
{
    public function deleting(AbstractDocument $document)
    {
        if ($document->isForceDeleting()) {
            foreach ($this->children as $childrenRelationship) {
                $document->{$childrenRelationship}()->delete();
            }
        } else {
            foreach ($this->children as $childrenRelationship) {
                $document->{$childrenRelationship}()->update([
                    'is_disabled' => true
                ]);
            }
        }
    }

    public function restoring(AbstractDocument $document)
    {
        foreach ($this->children as $$childrenRelationship) {
            $document->{$childrenRelationship}()->update([
                'is_disabled' => false
            ]);
        }
    }
}