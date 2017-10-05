<?php

namespace App\Domain\Model\Documents\Shared;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Infrastructure\Persistence\Traits\HasCustomRelations;
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

abstract class AbstractDocument extends Model
{
    use HasCustomRelations;

    protected $primaryKey = 'uuid';
    public $incrementing = false;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'archived_at'
    ];

    protected $dispatchesEvents = [
        'created' => DocumentWasCreated::class,
        'updated' => DocumentWasUpdated::class,
        'saved' => DocumentWasSaved::class,
        'deleting' => DocumentIsDeleting::class,
        'deleted' => DocumentWasDeleted::class,
        'restored' => DocumentWasRestored::class,
    ];

    abstract public function transform();

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