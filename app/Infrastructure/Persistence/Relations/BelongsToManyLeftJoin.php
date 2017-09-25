<?php

namespace App\Infrastructure\Persistence\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToManyLeftJoin extends BelongsToMany
{
    /**
     * Set the join clause for the relation query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|null  $query
     * @return $this
     */
    protected function performJoin($query = null)
    {
        $query = $query ?: $this->query;

        // We need to join to the intermediate table on the related model's primary
        // key column with the intermediate table's foreign key for the related
        // model instance. Then we can set the "where" for the parent models.
        $baseTable = $this->related->getTable();

        $key = $baseTable.'.'.$this->relatedKey;

        $query->leftJoin($this->table, $key, '=', $this->getQualifiedRelatedPivotKeyName());

        return $this;
    }
}
