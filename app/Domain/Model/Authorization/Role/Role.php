<?php

namespace App\Domain\Model\Authorization\Role;

use App\Domain\Model\Documents\Shared\AbstractDocument;

use App\Domain\Constants\Permission\Scopes as PermissionScope;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authorization\Permission\Permission;

class Role extends AbstractDocument
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'roleable_type',
        'roleable_id'
    ];

    protected $dispatchesEvents = [];

    public function getTransformer()
    {
        return new RoleTransformer;
    }

    public function roleable()
    {
        return $this->morphTo();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    public function roles()
    {
        return $this->hasMany(self::class, 'parent_role_uuid');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_role_uuid');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Check if user instance has permission to do something
     * with given document or it's type
     */
    public function hasPermissionTo($action, $document, $scope = null)
    {
        if (is_string($document) && $scope === null) {
            $scope = current_company();
        }
        return $this->permissions()->can($action, $document, $scope)->exists();
    }

    /**
     * Get roles who are authorized for specified action
     */
    public function scopeWithPermissionTo($query, $action, $document, $scope = null)
    {
        return $query->whereHas('permissions', function ($query) use ($action, $document, $scope) {
            return $query->can($action, $document, $scope);
        });
    }

    public function scopeVisible($query, $user_uuid = null)
    {
        return parent::scopeVisible($query, $user_uuid)->where('roleable_type', '<>', User::class);
    }
}