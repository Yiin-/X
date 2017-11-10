<?php

namespace App\Domain\Model\Documents\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Infrastructure\Persistence\Traits\HasCustomRelations;
use App\Domain\Model\Documents\Shared\Traits\HasHistory;
use App\Domain\Model\Authentication\User\User;
use App\Domain\Model\Authentication\Company\Company;
use App\Domain\Model\Authorization\Permission\Permission;
use App\Domain\Constants\Permission\Actions as PermissionActions;
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

    protected $dispatchesEvents = [
        'deleting' => DocumentIsDeleting::class,
        'deleted' => DocumentWasDeleted::class,
        'restored' => DocumentWasRestored::class,
        'archived' => DocumentWasArchived::class,
        'unarchived' => DocumentWasUnarchived::class
    ];

    protected $documentEvents = [
        'created' => DocumentWasCreated::class,
        'updated' => DocumentWasUpdated::class,
        'saved' => DocumentWasSaved::class
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

    public function transform()
    {
        return fractal()
            ->item($this)
            ->transformWith($this->getTransformer());
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

    public function permissions()
    {
        return $this->morphMany(Permission::class, 'permissible', 'permissible_type', 'permissible_uuid');
    }

    public function scopeVisible($query, $user_uuid = null)
    {
        return $this->checkForPermission($query, $user_uuid, PermissionActions::VIEW);
    }

    public function scopeEditable($query, $user_uuid = null)
    {
        return $this->checkForPermission($query, $user_uuid, PermissionActions::EDIT);
    }

    public function scopeDeletable($query, $user_uuid = null)
    {
        return $this->checkForPermission($query, $user_uuid, PermissionActions::DELETE);
    }

    public function scopeExportable($query, $user_uuid = null)
    {
        return $this->checkForPermission($query, $user_uuid, PermissionActions::EXPORT);
    }

    public function scopeArchivable($query, $user_uuid = null)
    {
        return $this->checkForPermission($query, $user_uuid, PermissionActions::ARCHIVE);
    }

    public function checkForPermission($query, $userUuid, $permissionType)
    {
        if (! $userUuid) {
            $user = auth()->user();
        } else {
            $user = User::find($userUuid);
        }
        return $query->whereIn('company_uuid', $user->companies->pluck('uuid'));
    }

    public static function find($uuid, $columns = ['*'])
    {
        $model = new static;
        return $model->where($model->getKeyName(), $uuid)->first($columns);
    }

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