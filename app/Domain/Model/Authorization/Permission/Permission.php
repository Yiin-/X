<?php

namespace App\Domain\Model\Authorization\Permission;

use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authorization\Role\Role;

class Permission extends AbstractDocument
{
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'type',
        'permissible_uuid',
        'permissible_type'
    ];

    protected $dispatchesEvents = [];

    public function transform()
    {
        return [
            'type' => $this->type
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    public function users()
    {
        return $this->belongsToManyLeftJoin(User::class, 'user_permission')
                    ->belongsToManyThrough($this, Role::class, 'role_permission', 'user_role');
    }

    public function permissible()
    {
        return $this->morphTo();
    }

    /**
     * Permissions by permissible type and id
     * @param  mixed $query QueryBuilder object
     * @param  string|object $model Model or name of model class
     * @return mixed query
     */
    public function scopeOf($query, $model)
    {
        $query = $query->where('permissible_type',
            is_string($model)
            ? $model
            : get_class($model));

        if (is_object($model) && $model instanceof Model) {
            $query = $query->where('permissible_uuid', $model->getKey());
        }
        return $query;
    }
}