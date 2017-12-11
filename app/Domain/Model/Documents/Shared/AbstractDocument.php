<?php

namespace App\Domain\Model\Documents\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Infrastructure\Persistence\Traits\HasCustomRelations;
use App\Domain\Model\Documents\Shared\Traits\HasHistory;
use App\Domain\Model\Documents\Shared\Interfaces\BelongsToClient;
use App\Domain\Model\Documents\Client\Client;
use App\Domain\Model\Documents\Credit\Credit;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authorization\Permission\Permission;
use App\Domain\Constants\Permission\Actions as PermissionActions;
use App\Domain\Constants\Permission\Scopes as PermissionScopes;

use App\Domain\Events\Document\UserCreatedDocument;
use App\Domain\Events\Document\UserUpdatedDocument;
use App\Domain\Events\Document\UserSavedDocument;

use App\Domain\Events\Document\DocumentWasCreated;
use App\Domain\Events\Document\DocumentWasUpdated;
use App\Domain\Events\Document\DocumentWasSaved;
use App\Domain\Events\Document\DocumentIsDeleting;
use App\Domain\Events\Document\DocumentWasDeleted;
use App\Domain\Events\Document\DocumentWasRestored;
use App\Domain\Events\Document\DocumentWasArchived;
use App\Domain\Events\Document\DocumentWasUnarchived;

abstract class AbstractDocument extends Model
{
    use HasCustomRelations,
        HasHistory;

    protected $primaryKey = 'uuid';
    public $incrementing = false;

    public $restoredFromActivity = false;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'archived_at'
    ];

    /**
     * Broadcastable model events
     */
    protected $dispatchesEvents = [
        'created' => DocumentWasCreated::class,
        'updated' => DocumentWasUpdated::class,
        'saved' => DocumentWasSaved::class,
        'deleting' => DocumentIsDeleting::class,
        'deleted' => DocumentWasDeleted::class,
        'restored' => DocumentWasRestored::class,
        'archived' => DocumentWasArchived::class,
        'unarchived' => DocumentWasUnarchived::class
    ];

    /**
     * Custom events called from AbstractDocumentRepository to track activity
     */
    protected $documentEvents = [
        'created' => UserCreatedDocument::class,
        'updated' => UserUpdatedDocument::class,
        'saved' => UserSavedDocument::class
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($document) {
            if ($document->isDirty('archived_at')) {
                $document->fireArchiveEvent();
            }
        });
    }

    public function fireArchiveEvent()
    {
        if ($this->archived_at) {
            $this->fireModelEvent('archived');
        }
        else {
            $this->fireModelEvent('unarchived');
        }
    }

    public function getDocumentEvents()
    {
        return $this->documentEvents;
    }

    public function getDocumentEvent($event)
    {
        return array_key_exists($event, $this->documentEvents)
            ? $this->documentEvents[$event]
            : false;
    }

    abstract public function getTransformer();

    public function transform($flags = [])
    {
        return fractal()
            ->item($this)
            ->transformWith($this->getTransformer())
            ->parseExcludes(
                in_array('for_backup', $flags) && method_exists($this->getTransformer(), 'excludeForBackup')
                ? $this->getTransformer()->excludeForBackup()
                : []
            );
    }

    public function loadRelationships()
    {
        //
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get documents that are visible to specified user.
     */
    public function scopeVisible($query, $user_uuid = null)
    {
        return $this->checkForPermission($query, $user_uuid, PermissionActions::VIEW);
    }

    public function scopeEditable($query, $user_uuid = null, $scopeable = null)
    {
        return $this->checkForPermission($query, $user_uuid, PermissionActions::EDIT);
    }

    public function scopeDeletable($query, $user_uuid = null, $scopeable = null)
    {
        return $this->checkForPermission($query, $user_uuid, PermissionActions::DELETE);
    }

    public function scopeArchivable($query, $user_uuid = null)
    {
        return $this->checkForPermission($query, $user_uuid, PermissionActions::EDIT);
    }

    /**
     * Only query documents that user has access to.
     *
     * E.g. if user is assigned to X country, that has 10 clients,
     * and user is assigned to 3 of them, we should ignore other clients
     * and countries that we have no access to, even if our permission
     * scope covers them. <is accessable> has priority over <permission scope>
     */
    public function scopeAccessable($query, $user)
    {
        if (!($this instanceof Client || $this instanceof BelongsToClient)) {
            return $query;
        }

        /**
         * Filter by country
         */
        if (!$user->assign_all_countries) {
            $countries = $user->countries->pluck('id');

            if ($this instanceof Client) {
                $query->whereIn('country_id', $countries);
            }
            else {
                $query->whereHas('client', function ($query) use ($user, $countries) {
                    return $query->whereIn('country_id', $countries);
                });
            }
        }

        /**
         * Filter by client
         */
        if (!$user->assign_all_clients) {
            $clients = $user->clients->pluck('uuid');

            if ($this instanceof Client) {
                $query->whereIn('uuid', $clients);
            } else {
                $query->whereIn('client_uuid', $clients);
            }
        }

        return $query;
    }

    /**
     * Find only documents that user has permission to.
     */
    public function checkForPermission($query, $userUuid, $permissionType)
    {
        if (! $userUuid) {
            $user = auth()->user();
        } else {
            $user = User::find($userUuid);
        }

        /**
         * Find only documents that fits following queries
         */
        return $query->accessable($user)->where(function ($query) use ($user, $permissionType) {

            $query->whereRaw('0');

            /**
             * Go through every role user has
             */
            foreach ($user->roles as $role) {
                $permissions = $role->permissions()
                    ->where(function ($query) use ($permissionType) {
                        return $query->where('permission_type_id', $permissionType)
                            ->orWhereNull('permission_type_id');
                    })
                    ->where(function ($query) {
                        return $query->where('permissible_type', resource_name($this))
                            ->orWhereNull('permissible_type');
                    })
                    ->get();

                $query->fitsPermissions($permissions);
            }
        });
    }

    /**
     * Used to filter out documents that we have no access to.
     */
    public function scopeFitsPermissions($query, $permissions)
    {
        foreach ($permissions as $permission) {

            /**
             * Ignore permissions that are neither
             */
            if ($permission->permission_type_id !== null &&
                (int)$permission->permission_type_id !== PermissionActions::VIEW
            ) {
                continue;
            }

            switch ($permission->scope) {

            /**
             * If it's account level permission, get all documents under user account.
             *
             * Since documents doesn't have reference to the account they belong to,
             * we're quering for documents of all companies under user account.
             */
            case PermissionScopes::ACCOUNT:
                $query->orWhere(function ($query) use ($permission) {
                    return $query->whereHas('company', function ($query) use ($permission) {
                        return $query->where('account_uuid', $permission->scope_id);
                    });
                });
                break;

            /**
             * If permission scope is limited to company, simply query for documents of that company.
             */
            case PermissionScopes::COMPANY:
                $query->orWhere(function ($query) use ($permission) {
                    return $query->where('company_uuid', $permission->scope_id);
                });
                break;

            /**
             * Or just query for specific document
             */
            case PermissionScopes::DOCUMENT:
                $query->orWhere('uuid', $permission->scope_id);
                break;
            }
        }

        return $query;
    }

    public static function find($uuid, $columns = ['*'])
    {
        $model = new static;
        return $model->where($model->getKeyName(), $uuid)->first($columns);
    }
}