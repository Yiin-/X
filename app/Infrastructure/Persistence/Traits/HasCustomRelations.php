<?php

namespace App\Infrastructure\Persistence\Traits;

use App\Infrastructure\Persistence\Relations\BelongsToManyLeftJoin;

trait HasCustomRelations
{
    public function scopeBelongsToManyThrough($query, Model $parentInstance, $related, $parentRelatedTable = null, $relatedSelfTable = null) {
        $relatedInstance = $this->newRelatedInstance($related);

        $selfQualifiedKeyName = $this->getQualifiedKeyName();

        $parentTablePart = Str::snake(class_basename($parentInstance));
        $relatedTablePart = Str::snake(class_basename($relatedInstance));
        $selfTablePart = Str::snake(class_basename($this));

        $parentRelatedTable = $parentRelatedTable ?? implode('_', [$relatedTablePart, $parentTablePart]);
        $relatedSelfTable = $relatedSelfTable ?? implode('_', [$relatedTablePart, $selfTablePart]);

        $selfForeignKey = $this->getForeignKey();
        $parentForeignKey = $parentInstance->getForeignKey();
        $relatedForeignKey = $relatedInstance->getForeignKey();

        return $query->orWhereExists(
            function ($query) use (
                $parentInstance,
                $selfQualifiedKeyName,
                $parentRelatedTable,
                $relatedSelfTable,
                $selfForeignKey,
                $parentForeignKey,
                $relatedForeignKey
            ) {
            $query
                ->select("{$relatedSelfTable}.{$selfForeignKey}")
                ->from("{$relatedSelfTable}")
                ->whereRaw("{$selfQualifiedKeyName} = `{$relatedSelfTable}`.`{$selfForeignKey}`")
                ->whereIn("{$relatedSelfTable}.{$relatedForeignKey}",
                    function ($query) use (
                        $parentRelatedTable,
                        $relatedForeignKey,
                        $parentForeignKey,
                        $parentInstance
                    ) {
                        $query->select("{$parentRelatedTable}.{$relatedForeignKey}")
                              ->from("{$parentRelatedTable}");

                        if ($parentInstance->getKey()) {
                            $query->where("{$parentRelatedTable}.{$parentForeignKey}", $parentInstance->getKey());
                        }
                        else {
                            $query->whereRaw("`{$parentRelatedTable}`.`{$parentForeignKey}` = {$parentInstance->getQualifiedKeyName()}");
                        }
                    });
        });
    }
}