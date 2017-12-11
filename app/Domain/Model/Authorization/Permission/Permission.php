<?php

namespace App\Domain\Model\Authorization\Permission;

use App\Domain\Model\Documents\Shared\Interfaces\BelongsToClient;
use App\Domain\Model\Documents\Shared\AbstractDocument;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authorization\Role\Role;
use App\Domain\Model\Authentication\Account\Account;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Constants\Permission\Scopes as PermissionScope;

class Permission extends AbstractDocument
{
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'scope',
        'scope_id',
        'permissible_type',
        'permission_type_id'
    ];

    protected $hidden = [
        'id'
    ];

    protected $dispatchesEvents = [];
    protected $documentEvents = [];

    public function getTransformer()
    {
        return new PermissionTransformer;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    private function belongsToClient(AbstractDocument $document)
    {
        if ($document instanceof \App\Domain\Model\Documents\Client\Client) {
            return $document->uuid;
        }
        if ($document instanceof BelongsToClient) {
            return $document->client_uuid;
        }
        return null;
    }

    public function scopeOfUser($query, $user)
    {
        return $query->whereHas('roles', function ($query) use ($user) {
            // Not using whereHas('users') because private roles would not be included
            return $query->whereIn('uuid', $user->roles->pluck('uuid'));
        });
    }

    /**
     * Only select permisisons that can do specified action
     * in specified scope.
     */
    public function scopeCan($query, $action, $document, $scope = null)
    {
        if ($document instanceof AbstractDocument) {
            $company = false;

            if ($document->company) {
                $company = $document->company;
            }

            if ($document instanceof Role) {
                if ($document->roleable_type === Company::class) {
                    $company = $document->roleable;
                }
            }

            return $query->where(function ($query) use ($action, $document, $company) {
                return $query->when($company, function ($query) use ($action, $document, $company) {
                    /**
                     * First check if user has account level permission
                     */
                    return $query->where(function ($query) use ($action, $document, $company) {
                        return $query
                            ->where('scope', PermissionScope::ACCOUNT)
                            ->where('scope_id', $company->account_uuid)
                            ->where(function ($query) use ($document) {
                                return $query
                                    ->where('permissible_type', resource_name($document))
                                    ->orWhereNull('permissible_type');
                            })
                            ->where(function ($query) use ($action) {
                                return $query
                                    ->where('permission_type_id', $action)
                                    ->orWhereNull('permission_type_id');
                            });
                    });
                })
                /**
                 * Or maybe company level permission?
                 */
                ->orWhere(function ($query) use ($action, $document) {
                    return $query
                        ->where('scope', PermissionScope::COMPANY)
                        ->where('scope_id', $document->company_uuid)
                        ->where(function ($query) use ($document) {
                            return $query
                                ->where('permissible_type', resource_name($document))
                                ->orWhereNull('permissible_type');
                        })
                        ->where(function ($query) use ($action) {
                            return $query
                                ->where('permission_type_id', $action)
                                ->orWhereNull('permission_type_id');
                        });
                })
                /**
                 * Document?..
                 */
                ->orWhere(function ($query) use ($action, $document) {
                    return $query
                        ->where('scope', PermissionScope::DOCUMENT)
                        ->where('scope_id', $document->uuid)
                        ->where(function ($query) use ($document) {
                            return $query
                                ->where('permissible_type', resource_name($document))
                                ->orWhereNull('permissible_type');
                        })
                        ->where(function ($query) use ($action) {
                            return $query
                                ->where('permission_type_id', $action)
                                ->orWhereNull('permission_type_id');
                        });
                });
            });
        }
        else if ($scope instanceof Company || $scope instanceof Account || $scope instanceof Client) {
            /**
             * First check if we have account level permission for this action
             */
            return $query->where(function ($query) use ($action, $document, $scope) {
                return $query
                    ->where('scope', PermissionScope::ACCOUNT)
                    ->where('scope_id',
                        $scope instanceof Company
                        ? $scope->account_uuid
                        : $scope->uuid // instanceof Account (optimistically)
                    )
                    ->where(function ($query) use ($document) {
                        return $query
                            ->where('permissible_type', resource_name($document))
                            ->orWhereNull('permissible_type');
                    })
                    ->where(function ($query) use ($action) {
                        return $query
                            ->where('permission_type_id', $action)
                            ->orWhereNull('permission_type_id');
                    });
            })
            /**
             * Secondly if our scope is company, check for company level permission.
             */
            ->when($scope instanceof Company, function ($query) use ($action, $document, $scope) {
                return $query->orWhere(function ($query) use ($action, $document, $scope) {
                    return $query
                        ->where(function ($query) use ($scope) {
                            return $query->where(function ($query) use ($scope) {
                                return $query
                                    ->where('scope', PermissionScope::COMPANY)
                                    ->where('scope_id', $scope->uuid); // id of the Company
                            });
                        })
                        ->where(function ($query) use ($document) {
                            return $query
                                ->where('permissible_type', resource_name($document))
                                ->orWhereNull('permissible_type');
                        })
                        ->where(function ($query) use ($action) {
                            return $query
                                ->where('permission_type_id', $action)
                                ->orWhereNull('permission_type_id');
                        });
                });
            });
        }
        else {
            throw new \Exception('unknown scope');
        }
    }
}